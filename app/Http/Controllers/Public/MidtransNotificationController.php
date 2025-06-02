<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Midtrans\Config as MidtransConfig;

class MidtransNotificationController extends Controller
{
    public function __construct()
    {
        MidtransConfig::$serverKey = config('services.midtrans.server_key');
        MidtransConfig::$isProduction = config('services.midtrans.is_production', false);
    }

    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Received (Array Payload):', $payload);

        if (empty($payload) || !is_array($payload)) {
            $rawContent = $request->getContent();
            Log::warning('Midtrans Webhook - Payload kosong atau bukan array, mencoba decode raw content.', ['raw_content_length' => strlen($rawContent)]);
            $payload = json_decode($rawContent, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($payload)) {
                Log::error('Midtrans Webhook - Gagal decode JSON dari raw content atau bukan array.', ['json_error' => json_last_error_msg(), 'raw_content_preview' => substr($rawContent, 0, 500)]);
                return response()->json(['status' => 'error', 'message' => 'Invalid payload format.'], 400);
            }
            Log::info('Midtrans Webhook - Payload berhasil di-decode dari raw content:', $payload);
        }
        
        // Akses field langsung dari array $payload
        $transactionStatus = $payload['transaction_status'] ?? null;
        $orderIdMidtrans = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKeyFromMidtrans = $payload['signature_key'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (empty($orderIdMidtrans) || empty($transactionStatus) || empty($statusCode) || empty($grossAmount) || empty($signatureKeyFromMidtrans)) {
            Log::error('Midtrans Webhook - Field kunci tidak ada dalam payload.', $payload);
            return response()->json(['status' => 'error', 'message' => 'Notification data incomplete.'], 400);
        }

        Log::info('Midtrans Webhook Parsed Data from Array:', [
            'order_id_midtrans' => $orderIdMidtrans, 'transaction_status' => $transactionStatus, 'fraud_status' => $fraudStatus, 'status_code' => $statusCode
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

        // 2. Idempotency Check
        $finalPaymentStatuses = ['paid', 'settlement'];
        $finalOrderStatuses = ['selesai', 'delivered', 'cancelled', 'dibatalkan'];

        if (in_array($order->payment_status, $finalPaymentStatuses) || in_array($order->status, $finalOrderStatuses)) {
            if (!($order->payment_status === 'paid' && $transactionStatus === 'settlement')) {
                 Log::info("Midtrans Webhook: Order ID {$orderId} sudah dalam status final. Notifikasi Midtrans ({$transactionStatus}) diabaikan.");
                 return response()->json(['status' => 'ok', 'message' => 'Order already processed.']);
            }
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
                if ($fraudStatus == 'accept' || is_null($fraudStatus) || $fraudStatus == 'challenge') { 
                    $newPaymentStatus = ($transactionStatus == 'settlement') ? 'settlement' : 'paid';
                    $newOrderStatus = 'processing';
                    if ($fraudStatus == 'challenge') {
                        Log::warning("Midtrans Webhook: Order ID {$orderId} FRAUD STATUS 'challenge'. Marked as {$newPaymentStatus}, but needs admin review.");
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
                // TODO: Kirim email notifikasi ke pelanggan
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