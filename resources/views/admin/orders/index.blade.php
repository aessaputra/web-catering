@extends('admin.layouts.app')

@section('title', 'Manajemen Pesanan')

@section('page-header')
    <div class="page-pretitle">Transaksi</div>
    <h2 class="page-title">Manajemen Pesanan Aktif</h2>
@endsection

@section('page-actions')
    <div class="ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('admin.orders.archived') }}" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive me-2" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                    <path d="M10 12l4 0" />
                </svg>
                Lihat Arsip Pesanan
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pesanan Aktif</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari ID/Nama/Email..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm me-2" style="min-width: 150px;"
                        onchange="this.form.submit()">
                        <option value="all"
                            {{ request('status') == 'all' || !request()->filled('status') ? 'selected' : '' }}>Semua
                            Status</option>
                        @foreach ($orderStatuses as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                        Filter
                    </button>
                    @if (request('search') || (request('status') && request('status') != 'all'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link ms-1">Reset</a>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tgl Pesan</th>
                        <th>Tgl Acara</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ match (strtolower($order->status)) {
                                        'delivered', 'selesai' => 'green',
                                        'cancelled', 'dibatalkan' => 'red',
                                        'pending' => 'yellow',
                                        'processing', 'diproses' => 'blue',
                                        'shipped', 'dikirim' => 'purple',
                                        default => 'secondary',
                                    } }}-lt">
                                    {{ $orderStatuses[$order->status] ?? Str::title(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->isoFormat('D MMM YY') }}</td>
                            <td>{{ $order->event_date->isoFormat('D MMM YYYY') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-info d-inline-flex align-items-center"
                                    title="Lihat & Update Status">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-file-invoice me-1" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M9 7l1 0" />
                                        <path d="M9 13l6 0" />
                                        <path d="M13 17l2 0" />
                                    </svg>
                                    Detail
                                </a>

                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                    class="d-inline ms-1"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan Pesanan #{{ $order->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-warning d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-archive me-1" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                            <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                                            <path d="M10 12l4 0" />
                                        </svg>
                                        Arsipkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-shopping-cart-off" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 3l18 18" />
                                            <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path d="M15.001 17.006a2 2 0 1 0 2 2" />
                                            <path d="M17 17h-11v-11" />
                                            <path d="M6 5l.669 .669m2.015 2.016l2.316 .315h8l-2 7h-1.451" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">Belum ada pesanan aktif.</p>
                                    <p class="empty-subtitle text-muted">
                                        Semua pesanan baru akan muncul di sini. Anda juga bisa melihat <a
                                            href="{{ route('admin.orders.archived') }}">arsip pesanan</a>.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $orders->firstItem() }}</span>
                    sampai <span>{{ $orders->lastItem() }}</span>
                    dari <span>{{ $orders->total() }}</span> entri
                </p>
                <div class="ms-auto">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            @if ($orders->total() > 0)
                <div class="card-footer">
                    <p class="m-0 text-muted">
                        Menampilkan semua <span>{{ $orders->total() }}</span> entri aktif.
                    </p>
                </div>
            @endif
        @endif
    </div>
@endsection
