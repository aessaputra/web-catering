@extends('public.layouts.app') {{-- Menggunakan layout publik Anda --}}

@section('title', 'Dashboard Saya')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ $user->name }}!</h1>
            <p class="text-gray-600 mb-8">Ini adalah halaman dashboard Anda. Di sini Anda bisa melihat riwayat pesanan dan
                mengelola akun.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-orange-50 p-4 rounded-lg shadow">
                    <h3 class="font-semibold text-orange-700">Profil Saya</h3>
                    <p class="text-sm text-gray-600 mb-2">Perbarui informasi pribadi dan password Anda.</p>
                    <a href="{{ route('profile.edit') }}"
                        class="text-orange-600 hover:text-orange-700 font-medium text-sm">Kelola Profil &rarr;</a>
                </div>
                <div class="bg-green-50 p-4 rounded-lg shadow">
                    <h3 class="font-semibold text-green-700">Buat Pesanan Baru</h3>
                    <p class="text-sm text-gray-600 mb-2">Siap untuk memesan lagi? Lihat menu kami.</p>
                    <a href="{{ route('order.create') }}"
                        class="text-green-600 hover:text-green-700 font-medium text-sm">Pesan Sekarang &rarr;</a>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg shadow">
                    <h3 class="font-semibold text-blue-700">Pesanan Aktif</h3>
                    {{-- TODO: Logika untuk menampilkan pesanan aktif terbaru --}}
                    <p class="text-sm text-gray-600 mb-2">Cek status pesanan Anda yang sedang berjalan.</p>
                    <p class="text-sm text-gray-500">(Fitur ini akan segera hadir)</p>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Riwayat Pesanan Anda</h2>
            @if ($orders->isEmpty())
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak Ada Pesanan</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda belum pernah melakukan pemesanan.</p>
                    <div class="mt-6">
                        <a href="{{ route('menu.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Lihat Menu Kami
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Pesanan</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Pesan</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Acara</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->event_date->isoFormat('D MMM YYYY') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                            if ($order->status == 'pending') {
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                            } elseif (in_array($order->status, ['processing', 'shipped'])) {
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                            } elseif ($order->status == 'delivered' || $order->status == 'selesai') {
                                                $statusClass = 'bg-green-100 text-green-800';
                                            } elseif ($order->status == 'cancelled') {
                                                $statusClass = 'bg-red-100 text-red-800';
                                            }
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $orderStatuses[$order->status] ?? Str::title(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ $order->order_items_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('dashboard.orders.show', $order) }}"
                                            class="text-orange-600 hover:text-orange-800">Lihat Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
