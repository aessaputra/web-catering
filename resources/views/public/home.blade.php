@extends('public.layouts.app')

@section('title', $siteSettings['site_name'] ?? 'Beranda Katering')

@section('content')
    @php
        $heroImagePath = $siteSettings['hero_image_homepage'] ?? null;
        $heroImageUrl = null;
        if ($heroImagePath && Storage::disk('public')->exists($heroImagePath)) {
            $heroImageUrl = asset('storage/' . $heroImagePath);
        }
        // Fallback background jika tidak ada gambar hero
        $heroFallbackClasses = 'bg-gradient-to-br from-orange-500 via-red-500 to-rose-600';
    @endphp

    <section
        class="text-white min-h-[65vh] sm:min-h-[75vh] md:min-h-[90vh] flex flex-col items-center justify-center bg-cover bg-center bg-no-repeat relative py-12 sm:py-0"
        style="@if ($heroImageUrl) background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ $heroImageUrl }}'); @endif"
        @if (!$heroImageUrl) class="{{ $heroFallbackClasses }} text-white min-h-[60vh] sm:min-h-[70vh] md:min-h-[85vh] flex flex-col items-center justify-center relative py-12 sm:py-0" @endif>
        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold mb-4 md:mb-6 leading-tight tracking-tight"
                style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
                {{ $siteSettings['site_name'] ?? 'Selamat Datang di Katering Kami' }}
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-8 md:mb-10 font-light max-w-3xl mx-auto"
                style="text-shadow: 1px 1px 4px rgba(0,0,0,0.6);">
                {{ $siteSettings['site_description'] ?? 'Menyajikan hidangan lezat berkualitas untuk setiap momen spesial Anda.' }}
            </p>
            <div class="space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:justify-center sm:gap-4">
                <a href="{{ route('menu.index') }}"
                    class="inline-block w-full sm:w-auto bg-orange-500 text-white font-bold py-3.5 px-8 rounded-lg hover:bg-orange-600 active:bg-orange-700 focus:bg-orange-700 transition-all duration-300 text-base md:text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-orange-300">
                    Lihat Pilihan Menu 🍽️
                </a>
                <a href="{{ route('order.create') }}"
                    class="inline-block w-full sm:w-auto bg-white text-orange-600 font-bold py-3.5 px-8 rounded-lg hover:bg-orange-50 active:bg-orange-100 focus:bg-orange-100 transition-all duration-300 text-base md:text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-orange-300">
                    Pesan Sekarang 🛒
                </a>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Menu Unggulan <span
                        class="text-orange-500">Terfavorit</span></h2>
                <p class="text-lg text-gray-600">Pilihan terbaik dari dapur kami, diracik dengan cinta khusus untuk Anda.
                </p>
                <div class="mt-4 h-1.5 w-24 bg-orange-500 mx-auto rounded-full"></div>
            </div>
            @if (isset($featuredItems) && $featuredItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @foreach ($featuredItems as $item)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-orange-400/40 hover:-translate-y-2">
                            <div class="relative h-60 overflow-hidden">
                                <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/450x300.png?text=' . urlencode($item->name) }}"
                                    alt="{{ $item->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                                @if ($item->is_featured)
                                    <div
                                        class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-md shadow-md">
                                        ISTIMEWA
                                    </div>
                                @endif
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                                    <h3 class="text-lg font-semibold text-white mb-1 group-hover:text-orange-300 transition-colors"
                                        style="text-shadow: 1px 1px 1px rgba(0,0,0,0.8);">{{ $item->name }}</h3>
                                    @if ($item->menuCategory)
                                        <p class="text-xs text-orange-200 uppercase tracking-wider font-medium">
                                            {{ $item->menuCategory->name }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <p class="text-gray-600 text-sm mb-4 flex-grow leading-relaxed">
                                    {{ Str::limit($item->description, 90) }}</p>
                                <div class="mt-auto pt-4 border-t border-gray-200/80">
                                    <div class="flex justify-between items-center">
                                        <p class="text-xl font-bold text-orange-600">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </p>
                                        <a href="{{ route('order.create') }}?add-item={{ $item->id }}"
                                            class="inline-flex items-center justify-center px-5 py-2 rounded-md text-sm font-semibold text-white 
                                                  bg-orange-500 hover:bg-orange-600 active:bg-orange-700
                                                  focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1 
                                                  transition-all duration-300 transform hover:scale-105">
                                            Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-700">Menu Unggulan Belum Tersedia</h3>
                    <p class="mt-1 text-sm text-gray-500">Kami sedang menyiapkan menu-menu terbaik kami. Silakan cek kembali
                        nanti!</p>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-orange-500 rounded-2xl shadow-xl p-8 md:p-12 lg:p-16 text-white">
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">🎉 Promosi Spesial Kami! 🎉</h2>
                    <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto font-light">
                        {{ $promotionMessage ?? ($siteSettings['homepage_promotion_message'] ?? 'Dapatkan penawaran menarik untuk setiap pesanan catering Anda. Kualitas terbaik, harga bersahabat!') }}
                    </p>
                    <a href="{{ route('menu.index') }}#promotions"
                        class="inline-block bg-white text-orange-600 font-bold py-3 px-10 rounded-lg hover:bg-orange-100 active:bg-orange-200 focus:bg-orange-100 transition duration-300 text-lg shadow-lg hover:shadow-xl transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-orange-300">
                        Lihat Detail Promo
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Tambahan: "Mengapa Memilih Kami?" --}}
    <section class="py-16 md:py-24 bg-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Mengapa Memilih <span
                        class="text-orange-500">{{ $siteSettings['site_name'] ?? 'Kami' }}</span>?</h2>
                <p class="text-lg text-gray-600">Komitmen kami untuk kualitas dan kepuasan Anda.</p>
                <div class="mt-4 h-1.5 w-24 bg-orange-500 mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 text-center">
                <div
                    class="bg-white p-8 rounded-xl shadow-lg transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center mb-5">
                        <div class="bg-orange-100 text-orange-600 rounded-full p-4 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Bahan Segar Pilihan</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Kami hanya menggunakan bahan-bahan segar berkualitas
                        tinggi untuk setiap hidangan.</p>
                </div>
                <div
                    class="bg-white p-8 rounded-xl shadow-lg transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center mb-5">
                        <div class="bg-green-100 text-green-600 rounded-full p-4 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Halal & Higienis</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Semua menu kami diolah dengan standar kebersihan
                        tertinggi dan dijamin kehalalannya.</p>
                </div>
                <div
                    class="bg-white p-8 rounded-xl shadow-lg transform transition duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-center mb-5">
                        <div class="bg-blue-100 text-blue-600 rounded-full p-4 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.08-.94-1-1.5M7 15H4c0-1.5.42-2 .943-2.5M12 12a3 3 0 100-6 3 3 0 000 6zM7 15h3c1.183 0 2.179-.018 3.056-.06M17 15h-2.58M17 15a9 9 0 00-14 0" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelayanan Terbaik</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Tim kami yang ramah dan profesional siap membantu
                        mewujudkan acara impian Anda.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
