@extends('public.layouts.app')

@section('title', 'Dashboard Saya - ' . ($siteSettings['site_name'] ?? config('app.name')))

@section('content')
    <div class="bg-gray-100 min-h-screen">
        {{-- Hero Section untuk Dashboard --}}
        <section class="bg-gradient-to-r from-orange-500 to-red-500 text-white py-16 md:py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold">Halo, {{ $user->name }}!</h1>
                        <p class="mt-1 text-lg text-orange-100">Selamat datang di Dashboard Pelanggan Anda.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-orange-600 bg-white hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-orange-500 focus:ring-white">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            {{-- Quick Actions/Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <a href="{{ route('order.create') }}"
                    class="block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-500 text-white rounded-lg p-3">
                            <i class="fas fa-plus-circle fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Buat Pesanan Baru</h3>
                            <p class="text-sm text-gray-600">Siap untuk acara berikutnya? Pesan menu favorit Anda sekarang!
                            </p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('menu.index') }}"
                    class="block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 text-white rounded-lg p-3">
                            <i class="fas fa-book-open fa-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800">Lihat Daftar Menu</h3>
                            <p class="text-sm text-gray-600">Jelajahi semua pilihan hidangan lezat yang kami tawarkan.</p>
                        </div>
                    </div>
                </a>
                {{-- Card "Pesanan Aktif" dihilangkan sesuai permintaan --}}
            </div>

            {{-- Riwayat Pesanan --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl md:text-2xl font-semibold text-gray-700 flex items-center">
                        <i class="fas fa-history mr-3 text-orange-500"></i>Riwayat Pesanan Anda
                    </h2>
                </div>

                @if ($orders->isEmpty())
                    <div class="text-center py-12 px-6">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-800">Anda Belum Memiliki Riwayat Pesanan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai petualangan kuliner Anda bersama kami!</p>
                        <div class="mt-6">
                            <a href="{{ route('order.create') }}"
                                class="inline-flex items-center px-6 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-150">
                                <i class="fas fa-plus-circle mr-2"></i> Buat Pesanan Pertama Anda
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
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
                                        Total (Rp)</th>
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
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 text-right font-semibold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $statusClass = 'bg-gray-100 text-gray-800'; // Default
                                                $statusIcon = 'fas fa-clock'; // Default
                                                switch (strtolower($order->status)) {
                                                    case 'pending':
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        $statusIcon = 'fas fa-hourglass-half';
                                                        break;
                                                    case 'processing':
                                                    case 'diproses':
                                                        $statusClass = 'bg-blue-100 text-blue-800';
                                                        $statusIcon = 'fas fa-cogs';
                                                        break;
                                                    case 'shipped':
                                                    case 'dikirim':
                                                        $statusClass = 'bg-purple-100 text-purple-800';
                                                        $statusIcon = 'fas fa-truck';
                                                        break;
                                                    case 'delivered':
                                                    case 'selesai':
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        $statusIcon = 'fas fa-check-circle';
                                                        break;
                                                    case 'cancelled':
                                                    case 'dibatalkan':
                                                        $statusClass = 'bg-red-100 text-red-800';
                                                        $statusIcon = 'fas fa-times-circle';
                                                        break;
                                                }
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                <i class="{{ $statusIcon }} mr-1.5"></i>
                                                {{ $orderStatuses[$order->status] ?? Str::title(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $order->order_items_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('dashboard.orders.show', $order) }}"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                                <i class="fas fa-eye mr-1.5"></i>Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($orders->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $orders->links() }} {{-- Laravel akan menggunakan view paginasi default (Tailwind jika Breeze) --}}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
