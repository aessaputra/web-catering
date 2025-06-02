@extends('public.layouts.app')

@section('title', 'Pembayaran Pesanan #' . $order->id)

@push('styles')
    {{-- Script Midtrans Snap.js --}}
    <script type="text/javascript"
        src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}"></script>
@endpush

@section('content')
    <div class="py-12 bg-gray-50 min-h-[calc(100vh-var(--header-height,0px)-var(--footer-height,0px))] flex items-center">
        <div class="max-w-lg mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-xl p-8 md:p-10 text-center">
                <div class="mb-6">
                    @if (isset($siteSettings['site_logo']) &&
                            $siteSettings['site_logo'] &&
                            Storage::disk('public')->exists($siteSettings['site_logo']))
                        <img src="{{ asset('storage/' . $siteSettings['site_logo']) }}"
                            alt="Logo {{ $siteSettings['site_name'] ?? config('app.name') }}"
                            class="h-12 w-auto mx-auto mb-4 sm:h-14">
                    @else
                        <h2 class="text-3xl font-bold text-orange-600 mb-2">
                            {{ $siteSettings['site_name'] ?? 'Catering Lezat' }}
                        </h2>
                    @endif
                    <p class="text-xl font-semibold text-gray-700">Selesaikan Pembayaran Anda</p>
                </div>

                <div class="bg-orange-50 p-6 rounded-lg mb-6 text-left">
                    <p class="text-sm text-gray-700 mb-1">Nomor Pesanan: <strong
                            class="text-gray-900">#{{ $order->id }}</strong></p>
                    <p class="text-sm text-gray-700">Nama Pemesan: <strong
                            class="text-gray-900">{{ $order->customer_name }}</strong></p>
                    <p class="text-lg font-bold text-orange-600 mt-2">Total Tagihan: Rp
                        {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>

                <p class="text-sm text-gray-600 mb-6">
                    Klik tombol "Lanjutkan Pembayaran" di bawah ini. Anda akan diarahkan ke halaman pembayaran aman
                    Midtrans.
                </p>

                <button id="pay-button"
                    class="w-full inline-flex items-center justify-center py-3.5 px-6 border border-transparent rounded-lg shadow-lg text-lg font-semibold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 focus:ring-offset-2 transition duration-150 ease-in-out">
                    <i class="fas fa-credit-card mr-2"></i>Lanjutkan Pembayaran
                </button>

                <div class="mt-8">
                    <a href="{{ route('dashboard.orders.show', $order) }}"
                        class="text-sm text-gray-500 hover:text-orange-500 underline">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    var payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function () {
            if (window.snap) {
                window.snap.pay('{{ $order->payment_token }}', {
                    onSuccess: function(result){
                        console.log('Midtrans Payment Success (Client):', result);
                        // Arahkan ke rute payment.finish, Midtrans akan tambah query params
                        // Kita juga bisa tambahkan app_order_id untuk kemudahan di controller callback
                        window.location.href = '{{ route("payment.finish") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=' + result.transaction_status + '&app_order_id={{ $order->id }}';
                    },
                    onPending: function(result){
                        console.log('Midtrans Payment Pending (Client):', result);
                        window.location.href = '{{ route("payment.unfinish") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=' + result.transaction_status + '&app_order_id={{ $order->id }}';
                    },
                    onError: function(result){
                        console.log('Midtrans Payment Error (Client):', result);
                        window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id + '&status_code=' + result.status_code + '&transaction_status=' + result.transaction_status + '&app_order_id={{ $order->id }}';
                    },
                    onClose: function(){
                        console.log('Midtrans Popup Closed by User for App Order ID: {{ $order->id }}');
                        // Arahkan ke unfinish, mungkin dengan app_order_id jika kita bisa pass
                        window.location.href = '{{ route("payment.unfinish") }}?order_id={{ $order->midtrans_order_id ?? $order->id."-TMP" }}&status_code=202&transaction_status=cancelled_by_user&app_order_id={{ $order->id }}';
                    }
                });
            } else { /* ... error snap.js tidak termuat ... */ }
        });
        // Pertimbangkan untuk auto-click tombol bayar jika UX-nya diinginkan
        // setTimeout(() => { payButton.click(); }, 500);
    }
  });
</script>
@endpush
