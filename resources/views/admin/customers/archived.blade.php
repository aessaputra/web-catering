@extends('admin.layouts.app')

@section('title', 'Arsip Pelanggan')

@section('page-header')
    <div class="page-pretitle">Pengguna</div>
    <h2 class="page-title">Pelanggan Diarsipkan</h2>
@endsection

@section('page-actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users-group" width="24" height="24"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
            <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M17 10h2a2 2 0 0 1 2 2v1" />
            <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
        </svg>
        Daftar Pelanggan Aktif
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan yang Diarsipkan</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.customers.archived') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari Nama/Email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                    @if (request()->has('search'))
                        <a href="{{ route('admin.customers.archived') }}" class="btn btn-sm btn-link ms-2">Reset</a>
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
                        <th>Diarsipkan Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($archivedCustomers as $customer)
                        <tr>
                            <td><span class="text-muted">{{ $customer->id }}</span></td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td>{{ $customer->deleted_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td>
                                <form action="{{ route('admin.customers.restore', $customer->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-rotate-clockwise" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4.05 11a8 8 0 1 1 .5 4m-.5 5v-5h5" />
                                        </svg>
                                        Pulihkan
                                    </button>
                                </form>
                                <form action="{{ route('admin.customers.force-delete', $customer->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('PERHATIAN: Aksi ini akan menghapus pelanggan {{ $customer->name }} secara permanen beserta semua data terkait yang mungkin tidak bisa dipulihkan. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-x"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
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
                            <td colspan="6" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-archive-off" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M8 4h10a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2m-4 0h-4a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2" />
                                            <path d="M12 12v8" />
                                            <path d="M8.248 8.25a3 3 0 0 1 3.752 -1.25a3 3 0 0 1 3.752 1.25" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">Tidak ada pelanggan yang diarsipkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            @if ($archivedCustomers->hasPages())
                <p class="m-0 text-muted">
                    Menampilkan <span>{{ $archivedCustomers->firstItem() }}</span>
                    sampai <span>{{ $archivedCustomers->lastItem() }}</span>
                    dari <span>{{ $archivedCustomers->total() }}</span> entri
                </p>
                <div class="ms-auto">
                    {{ $archivedCustomers->links() }}
                </div>
            @else
                @if ($archivedCustomers->total() > 0)
                    <p class="m-0 text-muted">
                        Menampilkan semua <span>{{ $archivedCustomers->total() }}</span> entri yang diarsipkan.
                    </p>
                @endif
            @endif
        </div>
    </div>
@endsection
