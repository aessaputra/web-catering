@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('page-header')
    <div class="page-pretitle">Pengguna</div>
    <h2 class="page-title">Manajemen Pelanggan</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari Nama/Email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                    @if (request()->has('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-link ms-2">Reset</a>
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
                        <th>Aksi</th>
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
                            <td>{{ $customer->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $customer->orders_count ?? $customer->orders->count() }} pesanan</td>
                            {{-- orders_count jika di-load dengan withCount --}}
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                    class="btn btn-sm btn-outline-azure">
                                    Lihat Detail
                                </a>
                                {{-- Tombol aksi lain seperti edit/delete pelanggan bisa ditambahkan di sini jika diperlukan --}}
                                {{-- Contoh:
                        <a href="#" class="btn btn-sm btn-outline-primary ms-1">Edit</a>
                        <form action="#" method="POST" class="d-inline ms-1" onsubmit="return confirm('Yakin hapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                        --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pelanggan yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $customers->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
