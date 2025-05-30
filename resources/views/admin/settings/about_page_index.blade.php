@extends('admin.layouts.app')

@section('title', 'Pengaturan Konten Halaman Tentang Kami')

@section('page-header')
    <div class="page-pretitle">Konten Halaman</div>
    <h2 class="page-title">Pengaturan Konten "Tentang Kami"</h2>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Konten "Tentang Kami"</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.about.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="settings_about_hero_title">Judul Hero "Tentang Kami"</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_hero_title') is-invalid @enderror"
                        name="settings_about[about_hero_title]" id="settings_about_hero_title"
                        value="{{ old('settings_about.about_hero_title', $settings['about_hero_title'] ?? '') }}">
                    @error('settings_about.about_hero_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_about_hero_subtitle_template">Subtitle Hero "Tentang
                        Kami"</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_hero_subtitle_template') is-invalid @enderror"
                        name="settings_about[about_hero_subtitle_template]" id="settings_about_hero_subtitle_template"
                        value="{{ old('settings_about.about_hero_subtitle_template', $settings['about_hero_subtitle_template'] ?? '') }}"
                        placeholder="Gunakan {appName} untuk nama situs">
                    <small class="form-hint">Placeholder `{appName}` akan diganti nama situs.</small>
                    @error('settings_about.about_hero_subtitle_template')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <div class="mb-3">
                    <label class="form-label" for="settings_about_history_title">Judul Bagian Sejarah</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_history_title') is-invalid @enderror"
                        name="settings_about[about_history_title]" id="settings_about_history_title"
                        value="{{ old('settings_about.about_history_title', $settings['about_history_title'] ?? '') }}">
                    @error('settings_about.about_history_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_about_history_content">Konten Bagian Sejarah</label>
                    <textarea class="form-control @error('settings_about.about_history_content') is-invalid @enderror"
                        name="settings_about[about_history_content]" id="settings_about_history_content" rows="7"
                        placeholder="Gunakan {appName} untuk nama situs. Anda bisa menggunakan Markdown sederhana untuk format.">{{ old('settings_about.about_history_content', $settings['about_history_content'] ?? '') }}</textarea>
                    @error('settings_about.about_history_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <div class="mb-3">
                    <label class="form-label" for="settings_about_vision_title">Judul Bagian Visi</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_vision_title') is-invalid @enderror"
                        name="settings_about[about_vision_title]" id="settings_about_vision_title"
                        value="{{ old('settings_about.about_vision_title', $settings['about_vision_title'] ?? '') }}">
                    @error('settings_about.about_vision_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_about_vision_content">Konten Bagian Visi</label>
                    <textarea class="form-control @error('settings_about.about_vision_content') is-invalid @enderror"
                        name="settings_about[about_vision_content]" id="settings_about_vision_content" rows="4">{{ old('settings_about.about_vision_content', $settings['about_vision_content'] ?? '') }}</textarea>
                    @error('settings_about.about_vision_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">
                <div class="mb-3">
                    <label class="form-label" for="settings_about_mission_title">Judul Bagian Misi</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_mission_title') is-invalid @enderror"
                        name="settings_about[about_mission_title]" id="settings_about_mission_title"
                        value="{{ old('settings_about.about_mission_title', $settings['about_mission_title'] ?? '') }}">
                    @error('settings_about.about_mission_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @for ($i = 1; $i <= 4; $i++)
                    <div class="mb-3">
                        <label class="form-label" for="settings_about_mission_point_{{ $i }}">Poin Misi
                            {{ $i }}</label>
                        <input type="text"
                            class="form-control @error('settings_about.about_mission_point_' . $i) is-invalid @enderror"
                            name="settings_about[about_mission_point_{{ $i }}]"
                            id="settings_about_mission_point_{{ $i }}"
                            value="{{ old('settings_about.about_mission_point_' . $i, $settings['about_mission_point_' . $i] ?? '') }}">
                        @error('settings_about.about_mission_point_' . $i)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endfor

                <hr class="my-4">
                <div class="mb-3">
                    <label class="form-label" for="settings_about_team_title">Judul Bagian Tim</label>
                    <input type="text"
                        class="form-control @error('settings_about.about_team_title') is-invalid @enderror"
                        name="settings_about[about_team_title]" id="settings_about_team_title"
                        value="{{ old('settings_about.about_team_title', $settings['about_team_title'] ?? '') }}">
                    @error('settings_about.about_team_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_about_team_content_1">Konten Tim (Paragraf 1)</label>
                    <textarea class="form-control @error('settings_about.about_team_content_1') is-invalid @enderror"
                        name="settings_about[about_team_content_1]" id="settings_about_team_content_1" rows="4">{{ old('settings_about.about_team_content_1', $settings['about_team_content_1'] ?? '') }}</textarea>
                    @error('settings_about.about_team_content_1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="settings_about_team_content_2">Konten Tim (Paragraf 2)</label>
                    <textarea class="form-control @error('settings_about.about_team_content_2') is-invalid @enderror"
                        name="settings_about[about_team_content_2]" id="settings_about_team_content_2" rows="4">{{ old('settings_about.about_team_content_2', $settings['about_team_content_2'] ?? '') }}</textarea>
                    @error('settings_about.about_team_content_2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="card-footer text-end border-top pt-3 mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Konten Tentang Kami</button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- Tidak perlu @push('scripts') jika tidak ada JavaScript spesifik di halaman ini --}}
