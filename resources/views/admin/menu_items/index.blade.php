@extends('admin.layouts.app')

@section('title', 'Daftar Item Menu')

@section('page-header')
    <div class="page-pretitle">Manajemen Menu</div>
    <h2 class="page-title">Item Menu</h2>
@endsection

@section('page-actions')
    <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12 5l0 14"></path>
            <path d="M5 12l14 0"></path>
        </svg>
        Tambah Item Menu Baru
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Item Menu</h3>
            <div class="ms-auto">
                <form method="GET" action="{{ route('admin.menu-items.index') }}" class="d-flex">
                    <select name="category_id" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari nama item..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Item</th>
                        <th>Kategori</th>
                        <th>Harga (Rp)</th>
                        <th>Unggulan</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menuItems as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/80x60.png?text=N/A' }}"
                                    alt="{{ $item->name }}" class="avatar avatar-md" style="object-fit: cover;">
                            </td>
                            <td>
                                <a href="{{ route('admin.menu-items.edit', $item) }}">{{ $item->name }}</a>
                            </td>
                            <td>{{ $item->menuCategory->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->is_featured)
                                    <span class="badge bg-green-lt">Ya</span>
                                @else
                                    <span class="badge bg-red-lt">Tidak</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.menu-items.edit', $item) }}"
                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus item menu ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada item menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $menuItems->links() }}
        </div>
    </div>
@endsection
