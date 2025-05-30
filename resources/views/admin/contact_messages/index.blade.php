@extends('admin.layouts.app')

@section('title', 'Arsip Pesan Kontak')

@section('page-header')
    <div class="page-pretitle">Komunikasi</div>
    <h2 class="page-title">Arsip Pesan Kontak Masuk</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pesan</h3>
            <div class="ms-auto d-print-none">
                <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Cari nama/email/pesan..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm btn-link ms-2">Reset
                            Filter</a>
                    @endif
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pengirim</th>
                        <th>Email</th>
                        <th>Pesan (Singkat)</th>
                        <th>Status</th>
                        <th>Diterima Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contactMessages as $message)
                        <tr class="{{ !$message->is_read ? 'fw-bold table-warning' : '' }}"> {{-- Pesan belum dibaca ditebalkan dan diberi highlight --}}
                            <td><span class="text-muted">{{ $message->id }}</span></td>
                            <td>{{ $message->name }}</td>
                            <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                            <td>{{ Str::limit($message->message, 70) }}</td>
                            <td>
                                @if ($message->is_read)
                                    <span class="badge bg-green-lt">Sudah Dibaca</span>
                                @else
                                    <span class="badge bg-yellow-lt">Belum Dibaca</span>
                                @endif
                            </td>
                            <td>{{ $message->created_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $message) }}"
                                    class="btn btn-sm btn-outline-azure">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6s-6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6s6.6 2 9 6" />
                                    </svg>
                                    Lihat
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                            <path d="M12 12l0 7" />
                                            <path d="M12 12l0 -4" />
                                            <path d="M12 12l-7 0" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">Belum ada pesan kontak</p>
                                    <p class="empty-subtitle text-muted">
                                        Semua pesan yang dikirim melalui form kontak publik akan muncul di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $contactMessages->links() }}
        </div>
    </div>
@endsection
