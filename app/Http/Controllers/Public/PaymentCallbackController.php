<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Untuk Str::title

class PaymentCallbackController extends Controller
{
    // Method untuk menangani redirect FINISH dari Midtrans (atau onSuccess dari Snap.js)
    public function handleFinish(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id'); // order_id dari Midtrans
        $appOrderId = $request->query('app_order_id'); // ID order aplikasi Anda, jika dikirim dari Snap.js
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        Log::info('Midtrans/Snap FINISH Callback Received:', $request->all());

        $order = null;
        if ($appOrderId) {
            $order = Order::find($appOrderId);
        } elseif ($orderIdMidtrans) {
            $orderIdParts = explode('-', $orderIdMidtrans);
            $appOrderId = $orderIdParts[0];
            $order = Order::find($appOrderId);
        }

        if (!$order) {
            Alert::error('Pesanan Tidak Ditemukan', 'Pesanan yang Anda maksud tidak ditemukan atau informasi tidak lengkap.');
            return redirect()->route('home');
        }
        
        $order->refresh(); // Ambil status terbaru dari DB (yang idealnya diupdate webhook)

        if ($statusCode == '200' && ($transactionStatus == 'capture' || $transactionStatus == 'settlement')) {
            if($order->payment_status === 'paid' || $order->payment_status === 'settlement') {
                Alert::success('Pembayaran Berhasil!', 'Terima kasih! Pesanan Anda #' . $order->id . ' telah berhasil dibayar dan sedang kami proses.');
            } else if ($order->payment_status === 'pending' && $transactionStatus !== 'pending') {
                // Webhook mungkin belum sempat update, tapi client-side callback bilang sukses
                Alert::info('Pembayaran Diterima', 'Terima kasih! Pembayaran Anda untuk Pesanan #' . $order->id . ' sedang diverifikasi. Status akan segera diperbarui.');
            } else {
                 Alert::info('Status Pembayaran', 'Status pembayaran untuk Pesanan #' . $order->id . ' adalah ' . Str::title($order->payment_status) . '. Silakan cek dashboard Anda.');
            }
        } else if ($statusCode == '201' && $transactionStatus == 'pending') {
             Alert::info('Pembayaran Pending', 'Pesanan Anda #' . $order->id . ' menunggu pembayaran. Silakan selesaikan pembayaran Anda.');
        } else {
            $errorMessage = 'Pembayaran untuk Pesanan #' . $order->id . ' belum selesai atau terjadi masalah.';
            if ($transactionStatus) {
                $errorMessage .= ' Status: ' . Str::title(str_replace('_', ' ', $transactionStatus)) . '.';
            }
            Alert::error('Pembayaran Belum Selesai', $errorMessage);
            // Arahkan kembali ke halaman checkout untuk order ini agar bisa coba bayar lagi
            return redirect()->route('payment.checkout', ['orderId' => $order->id]); 
        }
        return redirect()->route('dashboard.orders.show', $order->id);
    }

    // Method untuk menangani redirect UNFINISH dari Midtrans (atau onClose dari Snap.js)
    public function handleUnfinish(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id');
        $appOrderId = $request->query('app_order_id');
        Log::info('Midtrans/Snap UNFINISH/CLOSE Callback Received:', $request->all());

        $order = null;
        if ($appOrderId) {
            $order = Order::find($appOrderId);
        } elseif ($orderIdMidtrans) {
            $orderIdParts = explode('-', $orderIdMidtrans);
            $appOrderId = $orderIdParts[0];
            $order = Order::find($appOrderId);
        }

        if ($order) {
            Alert::warning('Pembayaran Ditunda', 'Anda menutup jendela pembayaran. Pesanan #' . $order->id . ' masih menunggu pembayaran.');
            return redirect()->route('dashboard.orders.show', $order->id);
        }
        Alert::info('Pembayaran Ditunda', 'Anda belum menyelesaikan proses pembayaran.');
        return redirect()->route('home');
    }

    // Method untuk menangani redirect ERROR dari Midtrans (atau onError dari Snap.js)
    public function handleError(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id');
        $appOrderId = $request->query('app_order_id');
        $transactionStatus = $request->query('transaction_status');
        $statusCode = $request->query('status_code');
        Log::error('Midtrans/Snap ERROR Callback Received:', $request->all());
        
        $order = null;
        if ($appOrderId) {
            $order = Order::find($appOrderId);
        } elseif ($orderIdMidtrans) {
            $orderIdParts = explode('-', $orderIdMidtrans);
            $appOrderId = $orderIdParts[0];
            $order = Order::find($appOrderId);
        }

        if ($order) {
            $errorMessage = 'Terjadi kesalahan pada pembayaran untuk Pesanan #' . $order->id . '.';
            if ($transactionStatus) {
                $errorMessage .= ' Status: ' . Str::title(str_replace('_', ' ', $transactionStatus)) . '.';
            }
            if ($statusCode) {
                 $errorMessage .= ' Kode: ' . $statusCode . '.';
            }
            Alert::error('Pembayaran Gagal', $errorMessage . ' Silakan coba lagi atau hubungi kami.');
            return redirect()->route('payment.checkout', ['orderId' => $order->id]);
        }
        Alert::error('Pembayaran Gagal', 'Terjadi kesalahan pada proses pembayaran. Silakan coba lagi.');
        return redirect()->route('order.create');
    }
}