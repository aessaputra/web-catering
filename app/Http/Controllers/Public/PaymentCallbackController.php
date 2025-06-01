<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    // Method untuk menangani redirect FINISH dari Midtrans
    public function handleFinish(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id'); // Ini adalah order_id dari Midtrans
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        Log::info('Midtrans FINISH Redirect:', $request->all());

        if (!$orderIdMidtrans) {
            Alert::error('Error', 'Informasi pesanan tidak lengkap dari redirect pembayaran.');
            return redirect()->route('home');
        }

        // Ekstrak ID order aplikasi Anda dari order_id Midtrans (jika formatnya "APP_ORDER_ID-RANDOM")
        $orderIdParts = explode('-', $orderIdMidtrans);
        $appOrderId = $orderIdParts[0];
        $order = Order::find($appOrderId);

        if (!$order) {
            Alert::error('Error', 'Pesanan tidak ditemukan.');
            return redirect()->route('home');
        }

        // PENTING: Verifikasi status transaksi dengan API Midtrans (GET Status)
        // sebelum benar-benar menganggapnya sukses, karena redirect bisa dimanipulasi.
        // Webhook adalah cara yang lebih aman untuk update status final.
        // Ini lebih sebagai UX redirect.

        if ($statusCode == '200' && ($transactionStatus == 'capture' || $transactionStatus == 'settlement')) {
            // Idealnya, status sudah diupdate oleh webhook.
            $order->refresh();
            if ($order->payment_status === 'paid' || $order->payment_status === 'settlement') {
                Alert::success('Pembayaran Berhasil!', 'Pesanan Anda #' . $order->id . ' telah berhasil dibayar dan sedang kami proses.');
            } else {
                Alert::info('Info Pembayaran', 'Terima kasih! Pembayaran Anda untuk Pesanan #' . $order->id . ' sedang diproses. Status pesanan akan segera kami perbarui.');
            }
            return redirect()->route('dashboard.orders.show', $order);
        } else if ($statusCode == '201' && $transactionStatus == 'pending') {
            Alert::info('Pembayaran Pending', 'Pesanan Anda #' . $order->id . ' menunggu pembayaran. Silakan selesaikan pembayaran Anda.');
            return redirect()->route('dashboard.orders.show', $order);
        } else {
            Alert::error('Pembayaran Gagal/Bermasalah', 'Terjadi masalah dengan pembayaran Anda untuk Pesanan #' . $order->id . '. Status: ' . $transactionStatus);
            return redirect()->route('payment.checkout', ['orderId' => $order->id]);
        }
    }

    // Method untuk menangani redirect UNFINISH dari Midtrans
    public function handleUnfinish(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id');
        Log::info('Midtrans UNFINISH Redirect:', $request->all());

        if (!$orderIdMidtrans) {
            Alert::warning('Pembayaran Belum Selesai', 'Anda belum menyelesaikan proses pembayaran.');
            return redirect()->route('home');
        }
        $orderIdParts = explode('-', $orderIdMidtrans);
        $appOrderId = $orderIdParts[0];
        $order = Order::find($appOrderId);

        if ($order) {
            Alert::warning('Pembayaran Belum Selesai', 'Anda menutup jendela pembayaran sebelum transaksi selesai untuk Pesanan #' . $order->id . '.');
            return redirect()->route('dashboard.orders.show', $order); // Arahkan ke detail pesanan
        }
        Alert::warning('Pembayaran Belum Selesai', 'Anda belum menyelesaikan proses pembayaran.');
        return redirect()->route('home');
    }

    // Method untuk menangani redirect ERROR dari Midtrans
    public function handleError(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id');
        Log::error('Midtrans ERROR Redirect:', $request->all());

        if (!$orderIdMidtrans) {
            Alert::error('Pembayaran Gagal', 'Terjadi kesalahan pada proses pembayaran.');
            return redirect()->route('order.create');
        }
        $orderIdParts = explode('-', $orderIdMidtrans);
        $appOrderId = $orderIdParts[0];
        $order = Order::find($appOrderId);

        if ($order) {
            Alert::error('Pembayaran Gagal', 'Terjadi kesalahan pada pembayaran untuk Pesanan #' . $order->id . '. Silakan coba lagi atau hubungi kami.');
            return redirect()->route('payment.checkout', ['orderId' => $order->id]);
        }
        Alert::error('Pembayaran Gagal', 'Terjadi kesalahan pada proses pembayaran. Silakan coba lagi.');
        return redirect()->route('order.create');
    }
}
