@extends('admin.layouts.app')

@section('title', 'Arsip Pesanan')

@section('page-header')
    <div class="page-pretitle">Manajemen</div>
    <h2 class="page-title">Arsip Pesanan</h2>
@endsection

@section('page-actions')
    <div class="ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list-check" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                    <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                    <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                    <path d="M11 6l9 0" />
                    <path d="M11 12l9 0" />
                    <path d="M11 18l9 0" />
                </svg>
                Daftar Pesanan Aktif
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pesanan yang Diarsipkan</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.orders.archived') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari ID/Nama/Email..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        @foreach ($orderStatuses as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.orders.archived') }}" class="btn btn-sm btn-link ms-1">Reset</a>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tgl Acara</th>
                        <th>Diarsipkan Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($archivedOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ strtolower($order->status) == 'delivered' || strtolower($order->status) == 'selesai' ? 'green' : (strtolower($order->status) == 'cancelled' ? 'red' : 'yellow') }}-lt">
                                    {{ $orderStatuses[$order->status] ?? Str::title($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->event_date->isoFormat('D MMM YYYY') }}</td>
                            <td>{{ $order->deleted_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td>
                                <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin memulihkan Pesanan #{{ $order->id }}?');">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-success d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-rotate-clockwise me-1" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4.05 11a8 8 0 1 1 .5 4m-.5 5v-5h5" />
                                        </svg>
                                        Pulihkan
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.force-delete', $order->id) }}" method="POST"
                                    class="d-inline ms-1"
                                    onsubmit="return confirm('PERHATIAN: Aksi ini akan menghapus Pesanan #{{ $order->id }} secara permanen beserta semua itemnya. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-trash-x me-1" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7h16" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            <path d="M10 12l4 4m0 -4l-4 4" />
                                        </svg>
                                        Hapus Permanen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty">
                                    <p class="empty-title">Tidak ada pesanan yang diarsipkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            @if ($archivedOrders->hasPages())
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $archivedOrders->firstItem() }}</span>
                    sampai <span>{{ $archivedOrders->lastItem() }}</span>
                    dari <span>{{ $archivedOrders->total() }}</span> entri
                </p>
                <div class="ms-auto">
                    {{ $archivedOrders->links() }}
                </div>
            @else
                @if ($archivedOrders->total() > 0)
                    <p class="m-0 text-muted">
                        Menampilkan semua <span>{{ $archivedOrders->total() }}</span> entri yang diarsipkan.
                    </p>
                @endif
            @endif
        </div>
    </div>
@endsection
