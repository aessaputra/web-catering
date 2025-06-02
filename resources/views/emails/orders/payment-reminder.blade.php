@component('mail::message')
# Pengingat Pembayaran untuk Pesanan #{{ $order->id }}

Halo {{ $order->customer_name }},

Ini adalah pengingat bahwa kami belum menerima pembayaran untuk pesanan Anda **#{{ $order->id }}** di **{{ $siteSettings['site_name'] ?? config('app.name') }}** yang dibuat pada {{ $order->created_at->isoFormat('D MMMM YYYY, HH:mm') }}.

Total tagihan Anda adalah: **Rp {{ number_format($order->total_amount, 0, ',', '.') }}**.

Untuk menyelesaikan pembayaran atau melihat detail pesanan Anda, silakan klik tombol di bawah ini:

@component('mail::button', ['url' => $orderUrl])
Lihat Detail Pesanan & Bayar
@endcomponent

Jika Anda sudah melakukan pembayaran, mohon abaikan email ini. Pembayaran Anda mungkin sedang dalam proses verifikasi.

Apabila Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami.

Terima kasih,<br>
Tim {{ $siteSettings['site_name'] ?? config('app.name') }}
@endcomponent