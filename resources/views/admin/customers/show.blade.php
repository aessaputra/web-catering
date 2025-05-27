@extends('admin.layouts.app')

@section('title', 'Detail Pelanggan: ' . $customer->name)

@section('page-header')
    <div class="page-pretitle">Pelanggan</div>
    <h2 class="page-title">Detail Pelanggan: {{ $customer->name }}</h2>
@endsection

@section('page-actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M5 12l14 0" />
            <path d="M5 12l6 6" />
            <path d="M5 12l6 -6" />
        </svg>
        Kembali ke Daftar Pelanggan
    </a>
@endsection

@section('content')
    <div class="row g-4">
        {{-- Kolom Informasi Pelanggan --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Dasar</h3>
                </div>
                <div class="card-body">
                    <p><strong>ID Pelanggan:</strong> {{ $customer->id }}</p>
                    <p><strong>Nama:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>No. Telepon:</strong> {{ $customer->phone ?? '-' }}</p>
                    <p><strong>Tanggal Registrasi:</strong> {{ $customer->created_at->format('d F Y, H:i') }}</p>
                    <p><strong>Email Terverifikasi:</strong>
                        @if ($customer->email_verified_at)
                            <span class="badge bg-green-lt">{{ $customer->email_verified_at->format('d M Y, H:i') }}</span>
                        @else
                            <span class="badge bg-red-lt">Belum Diverifikasi</span>
                        @endif
                    </p>
                    {{-- Tambahkan info lain jika perlu --}}
                </div>
            </div>
        </div>

        {{-- Kolom Riwayat Pesanan --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pesanan ({{ $customer->orders->count() }})</h3>
                </div>
                @if ($customer->orders->isEmpty())
                    <div class="card-body">
                        <p class="text-muted">Pelanggan ini belum memiliki riwayat pesanan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Tanggal Pesan</th>
                                    <th>Tanggal Acara</th>
                                    <th class="text-end">Total (Rp)</th>
                                    <th>Status</th>
                                    <th>Item</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer->orders as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->id }}</a></td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->event_date->format('d M Y') }}</td>
                                        <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @php
                                                $statusClass = 'secondary'; // Default
                                                if ($order->status == 'pending') {
                                                    $statusClass = 'warning';
                                                } elseif (in_array($order->status, ['processing', 'shipped'])) {
                                                    $statusClass = 'info';
                                                } elseif (
                                                    $order->status == 'delivered' ||
                                                    $order->status == 'selesai'
                                                ) {
                                                    $statusClass = 'success';
                                                } elseif ($order->status == 'cancelled') {
                                                    $statusClass = 'danger';
                                                }
                                                $statuses = [
                                                    'pending' => 'Pending',
                                                    'processing' => 'Diproses',
                                                    'shipped' => 'Dikirim',
                                                    'delivered' => 'Selesai',
                                                    'cancelled' => 'Dibatalkan',
                                                ];
                                            @endphp
                                            <span
                                                class="badge bg-{{ $statusClass }}-lt">{{ $statuses[$order->status] ?? Str::title($order->status) }}</span>
                                        </td>
                                        <td class="text-center">{{ $order->order_items_count }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                class="btn btn-sm btn-outline-azure">
                                                Lihat Pesanan
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Jika ingin paginasi untuk riwayat pesanan, controller perlu diubah untuk mem-paginate relasi orders --}}
                @endif
            </div>
        </div>
    </div>
@endsection
