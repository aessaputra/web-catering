@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('page-header')
    <div class="page-pretitle">Pesanan</div>
    <h2 class="page-title">Detail Pesanan #{{ $order->id }}</h2>
@endsection

@section('page-actions')
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M5 12l14 0" />
            <path d="M5 12l6 6" />
            <path d="M5 12l6 -6" />
        </svg>
        Kembali ke Daftar Pesanan
    </a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Item yang Dipesan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Nama Item</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan (Rp)</th>
                                <th class="text-end">Subtotal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>
                                        @if ($item->menuItem)
                                            {{ $item->menuItem->name }}
                                            <div class="text-muted">{{ $item->menuItem->menuCategory->name ?? '' }}</div>
                                        @else
                                            <span class="text-danger">Item Menu Telah Dihapus</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end h3">Total Pesanan</td>
                                <td class="text-end h3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pelanggan & Pengiriman</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Tanggal Acara:</strong> {{ $order->event_date->format('d F Y') }}</p>
                    <p><strong>Alamat Pengiriman:</strong><br>{{ nl2br(e($order->delivery_address)) }}</p>
                    @if ($order->user)
                        <p><small class="text-muted">Dipesan oleh pengguna terdaftar: {{ $order->user->name }} (ID:
                                {{ $order->user_id }})</small></p>
                    @else
                        <p><small class="text-muted">Dipesan sebagai tamu.</small></p>
                    @endif
                    @if ($order->notes)
                        <hr class="my-2">
                        <p><strong>Catatan dari Pelanggan:</strong><br>{{ nl2br(e($order->notes)) }}</p>
                    @endif
                    <p class="mt-3"><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Status Pesanan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Saat Ini:</label>
                            @php
                                $currentStatusClass = 'secondary'; // Default
                                if ($order->status == 'pending') {
                                    $currentStatusClass = 'warning';
                                } elseif (in_array($order->status, ['processing', 'shipped'])) {
                                    $currentStatusClass = 'info';
                                } elseif ($order->status == 'delivered' || $order->status == 'selesai') {
                                    $currentStatusClass = 'success';
                                } elseif ($order->status == 'cancelled') {
                                    $currentStatusClass = 'danger';
                                }
                            @endphp
                            <h4><span
                                    class="badge bg-{{ $currentStatusClass }}-lt p-2">{{ $statuses[$order->status] ?? Str::title($order->status) }}</span>
                            </h4>
                        </div>

                        <div class="mb-3">
                            <label for="new_status" class="form-label">Ubah Status Ke:</label>
                            <select name="status" id="new_status"
                                class="form-select @error('status') is-invalid @enderror">
                                @foreach ($statuses as $statusCode => $statusLabel)
                                    <option value="{{ $statusCode }}"
                                        {{ $order->status == $statusCode ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
