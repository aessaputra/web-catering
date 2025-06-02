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
         $rawPayload = $request->getContent();
        Log::info('Midtrans Webhook Received (Raw Input String For handle method):', [$rawPayload]);
        
        $notification = null;

        // Cobalah untuk decode JSON secara manual terlebih dahulu
        $decodedPayload = json_decode($rawPayload, true); // Decode sebagai array asosiatif

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedPayload)) {
            Log::error('Midtrans Webhook - Failed to decode JSON payload or payload is not an array.', ['raw_payload' => $rawPayload, 'json_error' => json_last_error_msg()]);
            return response()->json(['status' => 'error', 'message' => 'Invalid JSON payload received.'], 400);
        }
        Log::info('Midtrans Webhook - JSON Payload Decoded Successfully (Manual Check):', $decodedPayload);


        // Sekarang coba buat instance Notification. Library seharusnya tetap membaca php://input
        // tapi kita sudah punya $decodedPayload untuk debugging jika $notification kosong.
        try {
            $notification = new MidtransNotificationPayload(); // Library akan membaca php://input
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook - Failed to instantiate Midtrans SDK Notification object: ' . $e->getMessage(), [
                'raw_payload' => $rawPayload,
                'exception_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => 'Failed to process notification payload (instantiation).'], 400);
        }
        
        // Ambil field penting dari objek $notification
        $transactionStatus = $notification->transaction_status ?? null;
        $orderIdMidtrans = $notification->order_id ?? null;
        $statusCode = $notification->status_code ?? null;
        $grossAmount = $notification->gross_amount ?? null;
        $signatureKeyFromMidtrans = $notification->signature_key ?? null;
        $fraudStatus = $notification->fraud_status ?? null;

        // Jika $notification->order_id masih kosong, coba ambil dari $decodedPayload sebagai fallback
        // Ini seharusnya tidak perlu jika library bekerja dengan benar.
        if (empty($orderIdMidtrans) && isset($decodedPayload['order_id'])) {
            Log::warning('Midtrans Webhook - Notification object did not populate order_id. Using manually decoded payload.');
            $orderIdMidtrans = $decodedPayload['order_id'];
            $transactionStatus = $decodedPayload['transaction_status'] ?? null;
            $statusCode = $decodedPayload['status_code'] ?? null;
            $grossAmount = $decodedPayload['gross_amount'] ?? null;
            $signatureKeyFromMidtrans = $decodedPayload['signature_key'] ?? null;
            $fraudStatus = $decodedPayload['fraud_status'] ?? null;
        }
        
        if (empty($orderIdMidtrans) || empty($transactionStatus) || empty($statusCode) || empty($grossAmount) || empty($signatureKeyFromMidtrans)) {
            Log::error('Midtrans Webhook - One or more key fields are missing from notification.', [
                'order_id_midtrans' => $orderIdMidtrans,
                'transaction_status' => $transactionStatus,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'signature_key_present' => !empty($signatureKeyFromMidtrans),
                'notification_object_vars' => get_object_vars($notification), // Untuk melihat apa yang ada di objek
                'decoded_payload_vars' => $decodedPayload // Untuk melihat apa yang ada di JSON manual
            ]);
            return response()->json(['status' => 'error', 'message' => 'Notification data incomplete after parsing.'], 400);
        }
        
        Log::info('Midtrans Webhook Parsed Data (Used for Processing):', [
            'order_id_midtrans' => $orderIdMidtrans, 'transaction_status' => $transactionStatus, 'fraud_status' => $fraudStatus, 'status_code' => $statusCode
        ]);

        // ... sisa logika (ekstrak orderId, find order, verifikasi signature, update status) tetap sama ...
        $orderIdParts = explode('-', $orderIdMidtrans);
        $orderId = $orderIdParts[0];
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning("Midtrans Webhook: Order aplikasi dengan ID {$orderId} (dari Midtrans order ID {$orderIdMidtrans}) tidak ditemukan.");
            return response()->json(['status' => 'ok', 'message' => 'Order not found, notification acknowledged.']);
        }

        // Verifikasi Signature Key
        $expectedSignatureKey = hash('sha512', $orderIdMidtrans . $statusCode . $grossAmount . config('services.midtrans.server_key'));
        if ($signatureKeyFromMidtrans !== $expectedSignatureKey) {
            Log::error("Midtrans Webhook: Invalid signature key. Order ID Midtrans: {$orderIdMidtrans}. App Order ID: {$orderId}. Expected: [{$expectedSignatureKey}], Got: [{$signatureKeyFromMidtrans}]");
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }
        Log::info("Midtrans Webhook: Signature key VERIFIED for App Order ID: {$orderId}");

        // Idempotency Check
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
            // ... (sisa logika update status) ...
            $newPaymentStatus = $originalPaymentStatus;
            $newOrderStatus = $originalOrderStatus;

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || is_null($fraudStatus) || $fraudStatus == 'challenge') {
                    $newPaymentStatus = ($transactionStatus == 'settlement') ? 'settlement' : 'paid';
                    $newOrderStatus = 'processing';
                    if ($fraudStatus == 'challenge') {
                        Log::warning("Midtrans Webhook: Order ID {$orderId} has FRAUD STATUS 'challenge'. Marked as {$newPaymentStatus}, but needs admin review.");
                    }
                } elseif ($fraudStatus == 'deny') {
                    $newPaymentStatus = 'failed'; $newOrderStatus = 'cancelled';
                }
            } elseif ($transactionStatus == 'pending') {
                $newPaymentStatus = 'pending';
                if($originalOrderStatus == 'pending_payment' || empty($originalOrderStatus)) $newOrderStatus = 'pending_payment';
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $newPaymentStatus = ($transactionStatus == 'cancel' ? 'cancelled' : $transactionStatus);
                $newOrderStatus = 'cancelled';
            }
            
            if ($newPaymentStatus !== $originalPaymentStatus || $newOrderStatus !== $originalOrderStatus) {
                $order->payment_status = $newPaymentStatus;
                $order->status = $newOrderStatus;
                $order->save();
                Log::info("Midtrans Webhook: Order ID {$orderId} status diupdate. New: payment_status='{$newPaymentStatus}', order_status='{$newOrderStatus}'. Old: payment_status='{$originalPaymentStatus}', order_status='{$originalOrderStatus}'");
            } else {
                Log::info("Midtrans Webhook: Tidak ada perubahan status untuk Order ID {$orderId}. Notif: {$transactionStatus}, Fraud: {$fraudStatus}. DB: {$originalPaymentStatus}, {$originalOrderStatus}");
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Midtrans Webhook: Gagal update database untuk Order ID {$orderId}. Error: " . $e->getMessage(), ['exception_trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'message' => 'Failed to update order in database'], 500);
        }

        return response()->json(['status' => 'ok', 'message' => 'Notification processed successfully']);
    }
}
