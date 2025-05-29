@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('page-header')
    <div class="page-pretitle">Konfigurasi</div>
    <h2 class="page-title">Pengaturan Dasar Website</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pengaturan</h3>
        </div>
        <div class="card-body">
            {{-- PENTING: Tambahkan enctype="multipart/form-data" untuk upload file --}}
            <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Field-field setting teks (Nama, Email, Deskripsi, dll.) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required" for="settings_site_name">Nama Catering/Website</label>
                            <input type="text" class="form-control @error('settings.site_name') is-invalid @enderror"
                                name="settings[site_name]" id="settings_site_name"
                                value="{{ old('settings.site_name', $settings['site_name'] ?? '') }}">
                            @error('settings.site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required" for="settings_contact_email">Email Kontak Utama</label>
                            <input type="email" class="form-control @error('settings.contact_email') is-invalid @enderror"
                                name="settings[contact_email]" id="settings_contact_email"
                                value="{{ old('settings.contact_email', $settings['contact_email'] ?? '') }}">
                            @error('settings.contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_site_description">Deskripsi Singkat Website</label>
                    <textarea class="form-control @error('settings.site_description') is-invalid @enderror"
                        name="settings[site_description]" id="settings_site_description" rows="3">{{ old('settings.site_description', $settings['site_description'] ?? '') }}</textarea>
                    @error('settings.site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required" for="settings_contact_whatsapp">Nomor WhatsApp</label>
                            <input type="text"
                                class="form-control @error('settings.contact_whatsapp') is-invalid @enderror"
                                name="settings[contact_whatsapp]" id="settings_contact_whatsapp"
                                value="{{ old('settings.contact_whatsapp', $settings['contact_whatsapp'] ?? '') }}">
                            <small class="form-hint">Misal: 081234567890 atau 6281234567890.</small>
                            @error('settings.contact_whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_address">Alamat Catering</label>
                            <textarea class="form-control @error('settings.address') is-invalid @enderror" name="settings[address]"
                                id="settings_address" rows="1">{{ old('settings.address', $settings['address'] ?? '') }}</textarea>
                            @error('settings.address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4"> {{-- Pemisah --}}

                <h4 class="mb-3">Logo Website</h4>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 md:mb-0">
                        <label class="form-label d-block mb-2">Logo Saat Ini:</label>
                        @if (!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                            <img id="logoPreviewDisplay" src="{{ asset('storage/' . $settings['site_logo']) }}"
                                alt="Logo Saat Ini" class="avatar avatar-xl border bg-white object-contain">
                        @else
                            <img id="logoPreviewDisplay" src="https://via.placeholder.com/100x100.png?text=No+Logo"
                                alt="Tidak Ada Logo" class="avatar avatar-xl border">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="site_logo_file">Ganti/Upload Logo Baru</label>
                            <input type="file" class="form-control @error('site_logo_file') is-invalid @enderror"
                                name="site_logo_file" id="site_logo_file" onchange="previewLogoForAdmin(event)">
                            <small class="form-hint">Format: JPG, PNG, GIF, SVG, WEBP. Maks 2MB.</small>
                            @error('site_logo_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if (!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                        <div class="col-md-3 align-self-end"> {{-- align-self-end agar sejajar dengan input file --}}
                            <div class="mb-3">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_current_logo"
                                        value="1">
                                    <span class="form-check-label">Hapus logo saat ini</span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                <hr class="my-4">

                {{-- BAGIAN BARU UNTUK HERO IMAGE HOMEPAGE --}}
                <h4 class="mb-3">Gambar Hero Halaman Beranda</h4>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 md:mb-0">
                        <label class="form-label d-block mb-2">Gambar Hero Saat Ini:</label>
                        <div>
                            @if (!empty($settings['hero_image_homepage']) && Storage::disk('public')->exists($settings['hero_image_homepage']))
                                <img id="heroImagePreviewDisplay"
                                    src="{{ asset('storage/' . $settings['hero_image_homepage']) }}"
                                    alt="Gambar Hero Saat Ini" class="img-fluid rounded border bg-white"
                                    style="max-height: 150px; object-fit: contain;">
                            @else
                                <img id="heroImagePreviewDisplay"
                                    src="https://via.placeholder.com/300x150.png?text=No+Hero+Image"
                                    alt="Tidak Ada Gambar Hero" class="img-fluid rounded border">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="hero_image_homepage_file">Ganti/Upload Gambar Hero Baru</label>
                            <input type="file"
                                class="form-control @error('hero_image_homepage_file') is-invalid @enderror"
                                name="hero_image_homepage_file" id="hero_image_homepage_file"
                                onchange="previewGenericImage(event, 'heroImagePreviewDisplay', https://via.placeholder.com/300x150.png?text=No+Hero+Image')">
                            <small class="form-hint">Format:
                                JPG, PNG, WEBP. Maks 3MB. Rekomendasi rasio 16:9 atau sesuai
                                desain Anda.</small>
                            @error('hero_image_homepage_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if (!empty($settings['hero_image_homepage']))
                        <div class="col-md-3 align-self-end">
                            <div class="mb-3">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_current_hero_image"
                                        value="1">
                                    <span class="form-check-label">Hapus gambar hero saat ini</span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                <hr class="my-4">

                {{-- Media Sosial & Lainnya --}}
                <h4 class="mt-4 mb-3 border-bottom pb-2">Media Sosial & Lainnya</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_instagram_url">URL Instagram</label>
                            <input type="url"
                                class="form-control @error('settings.instagram_url') is-invalid @enderror"
                                name="settings[instagram_url]" id="settings_instagram_url"
                                value="{{ old('settings.instagram_url', $settings['instagram_url'] ?? '') }}">
                            @error('settings.instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_facebook_url">URL Facebook</label>
                            <input type="url"
                                class="form-control @error('settings.facebook_url') is-invalid @enderror"
                                name="settings[facebook_url]" id="settings_facebook_url"
                                value="{{ old('settings.facebook_url', $settings['facebook_url'] ?? '') }}">
                            @error('settings.facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_Maps_url">URL Embed Google Maps</label>
                    <textarea class="form-control @error('settings.Maps_url') is-invalid @enderror" name="settings[Maps_url]"
                        id="settings_Maps_url" rows="3" placeholder="Masukkan HANYA URL dari atribut src iframe Google Maps...">{{ old('settings.Maps_url', $settings['Maps_url'] ?? '') }}</textarea>
                    @error('settings.Maps_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Halaman Beranda --}}
                <h4 class="mt-4 mb-3 border-bottom pb-2">Halaman Beranda</h4>
                <div class="mb-3">
                    <label class="form-label" for="settings_homepage_promotion_message">Pesan Promosi di Beranda</label>
                    <textarea class="form-control @error('settings.homepage_promotion_message') is-invalid @enderror"
                        name="settings[homepage_promotion_message]" id="settings_homepage_promotion_message" rows="3">{{ old('settings.homepage_promotion_message', $settings['homepage_promotion_message'] ?? '') }}</textarea>
                    @error('settings.homepage_promotion_message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer text-end border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewLogoForAdmin(event) {
            const reader = new FileReader();
            const output = document.getElementById('logoPreviewDisplay');
            reader.onload = function() {
                output.src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                @if (!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                    output.src = "{{ asset('storage/' . $settings['site_logo']) }}";
                @else
                    output.src = "https://via.placeholder.com/100x100.png?text=No+Logo";
                @endif
            }
        }

        // Fungsi preview generik untuk gambar lain seperti Hero Image
        function previewGenericImage(event, previewElementId, placeholderUrl) {
            const reader = new FileReader();
            const output = document.getElementById(previewElementId);
            reader.onload = function() {
                if (output) output.src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            } else {
                if (output) output.src = placeholderUrl;
            }
        }
    </script>
@endpush
