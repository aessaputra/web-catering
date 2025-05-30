{{-- resources/views/admin/settings/general_index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Pengaturan Umum & Branding')

@section('page-header')
    <div class="page-pretitle">Konfigurasi Situs</div>
    <h2 class="page-title">Pengaturan Umum & Branding</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pengaturan Umum</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.general.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3 class="mb-3 border-bottom pb-2">Pengaturan Umum</h3>
                {{-- Field: site_name, contact_email, site_description, contact_whatsapp, address --}}
                {{-- Salin dari index.blade.php lama Anda, pastikan nama input adalah settings[key_name] --}}
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

                <div class="mb-3">
                    <label class="form-label" for="settings_operating_hours">Jam Operasional</label>
                    <textarea class="form-control @error('settings.operating_hours') is-invalid @enderror" name="settings[operating_hours]"
                        id="settings_operating_hours" rows="3"
                        placeholder="Senin - Minggu: 08:00 - 23:00&#10;Sabtu: 09:00 - 15:00&#10;Minggu: Tutup">{{ old('settings.operating_hours', $settings['operating_hours'] ?? '') }}</textarea>
                    <small class="form-hint">Anda bisa menggunakan baris baru untuk memformat tampilan jam
                        operasional.</small>
                    @error('settings.operating_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h3 class="mt-4 mb-3 border-bottom pb-2">Branding & Tampilan</h3>
                {{-- Field: site_logo_file, hero_image_homepage_file --}}
                {{-- Salin dari index.blade.php lama Anda --}}
                <h4 class="mb-3">Logo Website</h4>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 md:mb-0">
                        <label class="form-label d-block mb-2">Logo Saat Ini:</label>
                        <div>
                            @if (!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                                <img id="logoPreviewDisplay" src="{{ asset('storage/' . $settings['site_logo']) }}"
                                    alt="Logo Saat Ini" class="avatar avatar-xl border bg-white object-contain">
                            @else
                                <img id="logoPreviewDisplay" src="https://via.placeholder.com/100x100.png?text=No+Logo"
                                    alt="Tidak Ada Logo" class="avatar avatar-xl border">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="site_logo_file">Ganti/Upload Logo Baru</label>
                            <input type="file" class="form-control @error('site_logo_file') is-invalid @enderror"
                                name="site_logo_file" id="site_logo_file" onchange="previewLogoForAdmin(event)">
                            {{-- Pastikan fungsi previewLogoForAdmin ada --}}
                            <small class="form-hint">Format: JPG, PNG, GIF, SVG, WEBP. Maks 2MB.</small>
                            @error('site_logo_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if (!empty($settings['site_logo']) && Storage::disk('public')->exists($settings['site_logo']))
                        <div class="col-md-3 align-self-end">
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
                <hr class="my-3">
                <h4 class="mb-3">Gambar Hero Halaman Beranda</h4>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 md:mb-0">
                        <label class="form-label d-block mb-2">Gambar Hero Saat Ini:</label>
                        <div>
                            @if (!empty($settings['hero_image_homepage']) && Storage::disk('public')->exists($settings['hero_image_homepage']))
                                <img id="heroImagePreviewDisplay"
                                    src="{{ asset('storage/' . $settings['hero_image_homepage']) }}"
                                    alt="Gambar Hero Saat Ini" class="img-fluid rounded border bg-white"
                                    style="max-height: 100px; object-fit: contain;">
                            @else
                                <img id="heroImagePreviewDisplay"
                                    src="https://via.placeholder.com/200x100.png?text=No+Hero" alt="Tidak Ada Gambar Hero"
                                    class="img-fluid rounded border">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="hero_image_homepage_file">Ganti/Upload Gambar Hero Baru</label>
                            <input type="file"
                                class="form-control @error('hero_image_homepage_file') is-invalid @enderror"
                                name="hero_image_homepage_file" id="hero_image_homepage_file"
                                onchange="previewGenericImage(event, 'heroImagePreviewDisplay', 'https://via.placeholder.com/200x100.png?text=No+Hero+Image')">
                            {{-- Pastikan fungsi previewGenericImage ada --}}
                            <small class="form-hint">Format: JPG, PNG, WEBP. Maks 3MB.</small>
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
                                    <span class="form-check-label">Hapus gambar hero</span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>


                <h3 class="mt-4 mb-3 border-bottom pb-2">Media Sosial & Peta</h3>
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

                <div class="card-footer text-end border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Umum</button>
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
<script>
    function previewLogoForAdmin(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreviewDisplay');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = 'https://via.placeholder.com/100x100.png?text=No+Logo';
        }
    }

    function previewGenericImage(event, previewElementId, placeholderUrl) {
        const input = event.target;
        const preview = document.getElementById(previewElementId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = placeholderUrl;
        }
    }
</script>
