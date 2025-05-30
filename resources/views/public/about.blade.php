@extends('public.layouts.app')

@section('title', $siteSettings['about_hero_title'] ?? 'Tentang Kami')

@section('content')
    @php
        $appName = $siteSettings['site_name'] ?? 'Catering Lezat';
        $aboutHeroSubtitle = isset($siteSettings['about_hero_subtitle_template'])
            ? str_replace('{appName}', $appName, $siteSettings['about_hero_subtitle_template'])
            : "Mengenal lebih dekat $appName";

        $historyContent = isset($siteSettings['about_history_content'])
            ? str_replace('{appName}', $appName, $siteSettings['about_history_content'])
            : 'Konten sejarah belum diatur.';
        // Menggunakan nl2br untuk mempertahankan baris baru dari textarea
        $historyContentFormatted = nl2br(e($historyContent));

        $teamContent1 = isset($siteSettings['about_team_content_1'])
            ? str_replace('{appName}', $appName, $siteSettings['about_team_content_1'])
            : '';
        $teamContent1Formatted = nl2br(e($teamContent1));

        $teamContent2 = isset($siteSettings['about_team_content_2'])
            ? str_replace('{appName}', $appName, $siteSettings['about_team_content_2'])
            : '';
        $teamContent2Formatted = nl2br(e($teamContent2));

    @endphp

    {{-- Hero Section untuk Halaman Tentang Kami --}}
    <section class="bg-gradient-to-r from-orange-400 to-red-500 text-white py-20 md:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-3">
                <i class="fas fa-info-circle mr-3"></i>{{ $siteSettings['about_hero_title'] ?? 'Tentang Kami' }}
            </h1>
            <p class="text-2xl md:text-3xl font-semibold text-orange-200">
                {{ $aboutHeroSubtitle }}
            </p>
        </div>
    </section>

    <div class="bg-white py-12 md:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Bagian Sejarah Kami --}}
            <section class="mb-12 md:mb-16">
                <div class="max-w-3xl mx-auto text-center mb-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                        <i
                            class="fas fa-landmark text-orange-500 mr-2"></i>{{ $siteSettings['about_history_title'] ?? 'Perjalanan Kami' }}
                    </h2>
                    <div class="mt-4 h-1 w-20 bg-orange-500 mx-auto rounded-full"></div>
                </div>
                <div
                    class="bg-gray-50 p-6 sm:p-8 rounded-xl shadow-lg text-gray-700 leading-relaxed space-y-4 text-justify prose max-w-none">
                    {{-- Gunakan {!! ... !!} karena kita menggunakan nl2br dan sudah melakukan e() sebelumnya --}}
                    {!! $historyContentFormatted !!}
                </div>
            </section>

            {{-- Bagian Visi & Misi --}}
            <section class="mb-12 md:mb-16">
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-start"> {{-- Ganti items-center ke items-start --}}
                    <div class="order-2 md:order-1">
                        <h2 class="text-3xl md:text-4xl font-bold text-orange-600 mb-6">
                            <i class="fas fa-bullseye mr-2"></i>{{ $siteSettings['about_vision_title'] ?? 'Visi' }} &
                            {{ $siteSettings['about_mission_title'] ?? 'Misi Kami' }}
                        </h2>
                        <div class="mb-6">
                            <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                                <i
                                    class="fas fa-eye text-orange-500 mr-3 text-xl"></i>{{ $siteSettings['about_vision_title'] ?? 'Visi' }}
                            </h3>
                            <p class="text-gray-700 leading-relaxed pl-8 border-l-4 border-orange-200">
                                {{ $siteSettings['about_vision_content'] ?? 'Visi belum diatur.' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                                <i
                                    class="fas fa-rocket text-orange-500 mr-3 text-xl"></i>{{ $siteSettings['about_mission_title'] ?? 'Misi' }}
                            </h3>
                            <ul class="space-y-3 text-gray-700 leading-relaxed pl-8">
                                @for ($i = 1; $i <= 4; $i++)
                                    @if (isset($siteSettings["about_mission_point_$i"]) && !empty(trim($siteSettings["about_mission_point_$i"])))
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                            <span>{{ $siteSettings["about_mission_point_$i"] }}</span>
                                        </li>
                                    @endif
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Bagian Tim Kami --}}
            <section class="py-12 bg-gray-50 rounded-xl shadow-lg">
                <div class="max-w-3xl mx-auto text-center mb-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                        <i
                            class="fas fa-users text-orange-500 mr-2"></i>{{ $siteSettings['about_team_title'] ?? 'Tim Profesional Kami' }}
                    </h2>
                    <div class="mt-4 h-1 w-20 bg-orange-500 mx-auto rounded-full"></div>
                </div>
                <div class="text-gray-700 leading-relaxed text-center max-w-2xl mx-auto space-y-4 prose">
                    {!! $teamContent1Formatted !!}
                    @if (!empty(trim($teamContent2)))
                        <p>{!! $teamContent2Formatted !!}</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
