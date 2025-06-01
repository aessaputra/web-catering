<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config as MidtransConfig;
use Midtrans\Notification as MidtransNotificationPayload; // Menggunakan alias untuk kejelasan

class MidtransNotificationController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production');
        // MidtransConfig::$isSanitized = true; // Defaultnya true
        // MidtransConfig::$is3ds = true;       // Defaultnya false, set true jika kartu kredit menggunakan 3DS
    }

    public function handle(Request $request)
    {
        $rawPayloadForLog = $request->getContent();
        Log::info('Midtrans Webhook Received (Raw):', [$rawPayloadForLog]);

        $notification = null;
        try {
            // Cara standar untuk mendapatkan notifikasi dari Midtrans PHP Library
            $notification = new MidtransNotificationPayload(); // TANPA ARGUMEN
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook - Gagal membuat instance Midtrans\Notification: ' . $e->getMessage(), ['raw_payload' => $rawPayloadForLog]);
            return response()->json(['status' => 'error', 'message' => 'Failed to instantiate notification object.'], 400);
        }

        // Periksa apakah properti penting ada setelah instansiasi
        if (!isset($notification->transaction_status) || !isset($notification->order_id) || !isset($notification->status_code) || !isset($notification->gross_amount) || !isset($notification->signature_key)) {
            Log::error('Midtrans Webhook - Notification object missing one or more key fields after instantiation.', [
                'notification_vars' => get_object_vars($notification), // Lihat properti apa saja yang ada
                'raw_payload_logged_earlier' => $rawPayloadForLog
            ]);
            return response()->json(['status' => 'error', 'message' => 'Notification data incomplete.'], 400);
        }

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;
        $orderIdMidtrans = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKeyFromMidtrans = $notification->signature_key;

        Log::info('Midtrans Webhook Parsed:', [
            'order_id_midtrans' => $orderIdMidtrans,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'status_code' => $statusCode
        ]);

        $orderIdParts = explode('-', $orderIdMidtrans);
        $orderId = $orderIdParts[0];

        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("Midtrans Webhook: Order aplikasi dengan ID {$orderId} (dari Midtrans order ID {$orderIdMidtrans}) tidak ditemukan.");
            return response()->json(['status' => 'ok', 'message' => 'Order not found, notification acknowledged.']);
        }

        // 1. Verifikasi Signature Key
        $expectedSignatureKey = hash('sha512', $orderIdMidtrans . $statusCode . $grossAmount . config('services.midtrans.server_key'));
        if ($signatureKeyFromMidtrans !== $expectedSignatureKey) {
            Log::error("Midtrans Webhook: Invalid signature. Order ID Midtrans: {$orderIdMidtrans}. App Order ID: {$orderId}. Expected: [{$expectedSignatureKey}], Got: [{$signatureKeyFromMidtrans}]");
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }
        Log::info("Midtrans Webhook: Signature key VERIFIED for App Order ID: {$orderId}");

        // 2. Idempotency Check (hindari update ganda jika status sudah final)
        if (in_array($order->payment_status, ['paid', 'settlement']) && !in_array($transactionStatus, ['settlement', 'capture'])) {
            Log::info("Midtrans Webhook: Order ID {$orderId} sudah lunas (payment_status: {$order->payment_status}). Notifikasi Midtrans ({$transactionStatus}) diabaikan.");
            return response()->json(['status' => 'ok', 'message' => 'Order already processed.']);
        }
        if ($order->payment_status === 'settlement' && $transactionStatus === 'settlement') {
            Log::info("Midtrans Webhook: Order ID {$orderId} sudah settlement. Notifikasi settlement duplikat diabaikan.");
            return response()->json(['status' => 'ok', 'message' => 'Order already settled.']);
        }


        DB::beginTransaction();
        try {
            $originalPaymentStatus = $order->payment_status;
            $originalOrderStatus = $order->status;

            $newPaymentStatus = $originalPaymentStatus;
            $newOrderStatus = $originalOrderStatus;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || $fraudStatus == 'challenge' || is_null($fraudStatus)) {
                    $newPaymentStatus = ($transactionStatus == 'settlement') ? 'settlement' : 'paid';
                    $newOrderStatus = 'processing'; // Atau 'confirmed_payment'
                    if ($fraudStatus == 'challenge') {
                        Log::warning("Midtrans Webhook: Order ID {$orderId} FRAUD STATUS 'challenge'. Marked as {$newPaymentStatus}, but needs admin review.");
                        // Anda mungkin ingin status khusus seperti 'payment_challenged'
                    }
                } elseif ($fraudStatus == 'deny') {
                    $newPaymentStatus = 'failed';
                    $newOrderStatus = 'cancelled';
                }
            } elseif ($transactionStatus == 'pending') {
                $newPaymentStatus = 'pending';
                // Biarkan status order aplikasi, atau set ke 'pending_payment' jika belum
                if ($originalOrderStatus == 'pending_payment' || empty($originalOrderStatus)) {
                    $newOrderStatus = 'pending_payment';
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $newPaymentStatus = ($transactionStatus == 'cancel' ? 'cancelled' : $transactionStatus);
                $newOrderStatus = 'cancelled';
            }

            $order->payment_status = $newPaymentStatus;
            $order->status = $newOrderStatus;
            $order->save();

            DB::commit();
            Log::info("Midtrans Webhook: Order ID {$orderId} status diupdate. New: payment_status='{$newPaymentStatus}', order_status='{$newOrderStatus}'. Old: payment_status='{$originalPaymentStatus}', order_status='{$originalOrderStatus}'");

            // TODO: Kirim email notifikasi ke pelanggan jika pembayaran sukses
            // if ($newPaymentStatus === 'paid' || $newPaymentStatus === 'settlement') {
            //     Mail::to($order->customer_email)->send(new OrderPaymentSuccessMail($order));
            // }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Midtrans Webhook: GAGAL update database untuk Order ID {$orderId}. Error: " . $e->getMessage(), [
                'notification_object_vars' => get_object_vars($notification),
                'exception_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Failed to update order in database'], 500);
        }

        return response()->json(['status' => 'ok', 'message' => 'Notification processed successfully']);
    }
}
