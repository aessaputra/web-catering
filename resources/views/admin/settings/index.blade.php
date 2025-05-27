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
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required" for="settings_site_name">Nama Catering/Website</label>
                            <input type="text" class="form-control @error('settings.site_name') is-invalid @enderror"
                                name="settings[site_name]" id="settings_site_name"
                                value="{{ old('settings.site_name', $settings['site_name'] ?? '') }}"
                                placeholder="Contoh: Catering Lezat">
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
                                value="{{ old('settings.contact_email', $settings['contact_email'] ?? '') }}"
                                placeholder="Contoh: info@catering.com">
                            @error('settings.contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="settings_site_description">Deskripsi Singkat Website (untuk
                        SEO/Footer)</label>
                    <textarea class="form-control @error('settings.site_description') is-invalid @enderror"
                        name="settings[site_description]" id="settings_site_description" rows="3"
                        placeholder="Deskripsi singkat tentang layanan catering Anda...">{{ old('settings.site_description', $settings['site_description'] ?? '') }}</textarea>
                    @error('settings.site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required" for="settings_contact_phone">Nomor Telepon Kontak</label>
                            <input type="text" class="form-control @error('settings.contact_phone') is-invalid @enderror"
                                name="settings[contact_phone]" id="settings_contact_phone"
                                value="{{ old('settings.contact_phone', $settings['contact_phone'] ?? '') }}"
                                placeholder="Contoh: 081234567890">
                            @error('settings.contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_address">Alamat Catering</label>
                            <textarea class="form-control @error('settings.address') is-invalid @enderror" name="settings[address]"
                                id="settings_address" rows="1" placeholder="Alamat lengkap catering Anda...">{{ old('settings.address', $settings['address'] ?? '') }}</textarea>
                            @error('settings.address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <h4 class="mt-4 mb-3 border-bottom pb-2">Media Sosial & Lainnya</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_instagram_url">URL Instagram</label>
                            <input type="url" class="form-control @error('settings.instagram_url') is-invalid @enderror"
                                name="settings[instagram_url]" id="settings_instagram_url"
                                value="{{ old('settings.instagram_url', $settings['instagram_url'] ?? '') }}"
                                placeholder="Contoh: https://instagram.com/cateringkeren">
                            @error('settings.instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="settings_facebook_url">URL Facebook</label>
                            <input type="url" class="form-control @error('settings.facebook_url') is-invalid @enderror"
                                name="settings[facebook_url]" id="settings_facebook_url"
                                value="{{ old('settings.facebook_url', $settings['facebook_url'] ?? '') }}"
                                placeholder="Contoh: https://facebook.com/cateringkeren">
                            @error('settings.facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_Maps_url">URL Embed Google Maps</label>
                    <textarea class="form-control @error('settings.Maps_url') is-invalid @enderror" name="settings[Maps_url]"
                        id="settings_Maps_url" rows="3" placeholder="Masukkan kode embed iframe Google Maps atau URL direct ke peta">{{ old('settings.Maps_url', $settings['Maps_url'] ?? '') }}</textarea>
                    @error('settings.Maps_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="mt-4 mb-3 border-bottom pb-2">Halaman Beranda</h4>
                <div class="mb-3">
                    <label class="form-label" for="settings_homepage_promotion_message">Pesan Promosi di Beranda</label>
                    <textarea class="form-control @error('settings.homepage_promotion_message') is-invalid @enderror"
                        name="settings[homepage_promotion_message]" id="settings_homepage_promotion_message" rows="3"
                        placeholder="Tulis pesan promosi singkat untuk ditampilkan di halaman beranda...">{{ old('settings.homepage_promotion_message', $settings['homepage_promotion_message'] ?? '') }}</textarea>
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
