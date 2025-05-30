{{-- resources/views/admin/contact_messages/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Pesan Kontak #' . $contactMessage->id)

@section('page-header')
    <div class="page-pretitle">Pesan Masuk</div>
    <h2 class="page-title">Detail Pesan dari: {{ $contactMessage->name }}</h2>
@endsection

@section('page-actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24"
            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M5 12l14 0" />
            <path d="M5 12l6 6" />
            <path d="M5 12l6 -6" />
        </svg>
        Kembali ke Arsip Pesan
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Pesan dari: <strong>{{ $contactMessage->name }}</strong></h3>
                        <p class="card-subtitle text-muted">
                            <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a> &bull;
                            Diterima: {{ $contactMessage->created_at->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm') }}
                            ({{ $contactMessage->created_at->diffForHumans() }})
                        </p>
                    </div>
                    <div class="card-actions ms-auto">
                        <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                                Hapus Pesan
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="markdown">
                        <h4 class="mb-3 text-muted">Isi Pesan:</h4>
                        <div class="border bg-light rounded p-3"
                            style="font-size: 0.9rem; line-height: 1.5; max-height: 400px; overflow-y: auto;">
                            {!! nl2br(e($contactMessage->message)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Tambahan</h3>
                </div>
                <div class="card-body">
                    <p><strong>ID Pesan:</strong> {{ $contactMessage->id }}</p>
                    <p><strong>Status:</strong>
                        @if ($contactMessage->is_read)
                            <span class="badge bg-green-lt">Sudah Dibaca</span>
                        @else
                            <span class="badge bg-yellow-lt">Belum Dibaca</span>
                        @endif
                    </p>
                    <p><strong>Diterima:</strong><br> {{ $contactMessage->created_at->isoFormat('D MMM YYYY, HH:mm:ss') }}
                    </p>
                    @if ($contactMessage->created_at->ne($contactMessage->updated_at) && $contactMessage->is_read)
                        <p class="mt-2 text-muted text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checks"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 12l5 5l10 -10" />
                                <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg>
                            Dibaca pada: {{ $contactMessage->updated_at->isoFormat('D MMM YYYY, HH:mm:ss') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
