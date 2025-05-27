@extends('admin.layouts.app')

@section('title', 'Manajemen Pesanan')

@section('page-header')
    <div class="page-pretitle">Ringkasan</div>
    <h2 class="page-title">Manajemen Pesanan</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Semua Pesanan</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari ID/Nama/Email..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $statusCode => $statusLabel)
                            <option value="{{ $statusCode }}" {{ request('status') == $statusCode ? 'selected' : '' }}>
                                {{ $statusLabel }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                    @if (request()->has('search') || request()->has('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link ms-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Tgl Acara</th>
                        <th>Total (Rp)</th>
                        <th>Status</th>
                        <th>Tgl Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><span class="text-muted">{{ $order->id }}</span></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td>{{ $order->event_date->format('d M Y') }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusClass = 'secondary'; // Default
                                    if ($order->status == 'pending') {
                                        $statusClass = 'warning';
                                    } elseif (in_array($order->status, ['processing', 'shipped'])) {
                                        $statusClass = 'info';
                                    } elseif ($order->status == 'delivered' || $order->status == 'selesai') {
                                        $statusClass = 'success';
                                    }
                                    // 'selesai' jika Anda menggunakan itu
                                    elseif ($order->status == 'cancelled') {
                                        $statusClass = 'danger';
                                    }
                                @endphp
                                <span
                                    class="badge bg-{{ $statusClass }}-lt">{{ $statuses[$order->status] ?? Str::title($order->status) }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-azure">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
