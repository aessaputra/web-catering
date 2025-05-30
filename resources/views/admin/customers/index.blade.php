@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('page-header')
    <div class="page-pretitle">Pengguna</div>
    <h2 class="page-title">Manajemen Pelanggan Aktif</h2>
@endsection

@section('page-actions')
    <div class="ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('admin.customers.archived') }}" class="btn btn-outline-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-archive" width="24"
                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                    <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                    <path d="M10 12l4 0" />
                </svg>
                Lihat Arsip Pelanggan
            </a>
            {{-- Jika Anda memiliki fitur tambah pelanggan dari admin --}}
            {{-- <a href="#" class="btn btn-primary d-sm-none d-md-inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Tambah Pelanggan Baru
        </a> --}}
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan Aktif</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari Nama/Email Pelanggan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                        Cari
                    </button>
                    @if (request()->has('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-link ms-1">Reset</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Terdaftar Pada</th>
                        <th>Total Pesanan</th>
                        <th class="w-1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td><span class="text-muted">{{ $customer->id }}</span></td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>{{ $customer->created_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td class="text-center">{{ $customer->orders_count ?? $customer->orders->count() }}</td>
                            <td class="text-end"> {{-- Mengatur agar tombol rata kanan jika diinginkan --}}
                                <div class="btn-list flex-nowrap justify-content-end"> {{-- justify-content-end juga untuk rata kanan --}}
                                    <a href="{{ route('admin.customers.show', $customer) }}"
                                        class="btn btn-sm btn-outline-azure" title="Lihat Detail Pelanggan">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-eye me-1" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6s-6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6s6.6 2 9 6" />
                                        </svg>
                                        Lihat
                                    </a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan pelanggan {{ $customer->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                            title="Arsipkan Pelanggan">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-archive me-1" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                                <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                                                <path d="M10 12l4 0" />
                                            </svg>
                                            Arsipkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-users-off" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M14.274 10.291a4 4 0 1 0 -5.549 -5.549m5.272 2.26a4 4 0 0 0 -2.223 -2.211" />
                                            <path
                                                d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 1.147 .165m2.656 2.651a3.988 3.988 0 0 1 .197 1.184v2" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">Belum ada pelanggan aktif.</p>
                                    <p class="empty-subtitle text-muted">
                                        Pelanggan yang mendaftar akan muncul di sini. Anda juga bisa melihat <a
                                            href="{{ route('admin.customers.archived') }}">arsip pelanggan</a>.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $customers->firstItem() }}</span>
                    sampai <span>{{ $customers->lastItem() }}</span>
                    dari <span>{{ $customers->total() }}</span> entri
                </p>
                <div class="ms-auto">
                    {{ $customers->links() }}
                </div>
            </div>
        @else
            @if ($customers->total() > 0)
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">
                        Menampilkan semua <span>{{ $customers->total() }}</span> entri aktif.
                    </p>
                </div>
            @endif
        @endif
    </div>
@endsection
