@extends('public.layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500">Dipesan pada:
                        {{ $order->created_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-sm text-orange-600 hover:text-orange-800 font-medium">&larr;
                    Kembali ke Dashboard</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Pelanggan</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Pengiriman & Acara</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Tanggal Acara:</strong> {{ $order->event_date->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p><strong>Alamat Pengiriman:</strong><br>{{ nl2br(e($order->delivery_address)) }}</p>
                    </div>
                </div>
                @if ($order->notes)
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Catatan Tambahan</h3>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md">{{ nl2br(e($order->notes)) }}</p>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Status Pesanan</h3>
                    @php
                        $statusClass = 'bg-gray-200 text-gray-800'; // Default
                        if ($order->status == 'pending') {
                            $statusClass = 'bg-yellow-200 text-yellow-800';
                        } elseif (in_array($order->status, ['processing', 'shipped'])) {
                            $statusClass = 'bg-blue-200 text-blue-800';
                        } elseif ($order->status == 'delivered' || $order->status == 'selesai') {
                            $statusClass = 'bg-green-200 text-green-800';
                        } elseif ($order->status == 'cancelled') {
                            $statusClass = 'bg-red-200 text-red-800';
                        }
                    @endphp
                    <p><span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                            {{ $orderStatuses[$order->status] ?? Str::title(str_replace('_', ' ', $order->status)) }}
                        </span></p>
                </div>
            </div>


            <h3 class="text-xl font-semibold text-gray-700 mb-4 mt-8">Item yang Dipesan</h3>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($item->menuItem && $item->menuItem->image_path)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover"
                                                    src="{{ asset('storage/' . $item->menuItem->image_path) }}"
                                                    alt="{{ $item->menuItem->name }}">
                                            </div>
                                        @else
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->menuItem->name ?? 'Item Tidak Ditemukan' }}
                                            </div>
                                            @if ($item->menuItem && $item->menuItem->menuCategory)
                                                <div class="text-xs text-gray-500">
                                                    {{ $item->menuItem->menuCategory->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Rp
                                    {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700 uppercase">
                                Total Keseluruhan</td>
                            <td class="px-6 py-3 text-right text-lg font-bold text-gray-900">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('order.create') }}"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Pesan Lagi
                </a>
            </div>
        </div>
    </div>
@endsection
