@extends('public.layouts.app')

@section('title', 'Daftar Menu Lengkap')

@section('content')
    <div class="bg-gray-50 min-h-screen"> {{-- Latar belakang keseluruhan halaman --}}
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="text-center mb-12 md:mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4">
                    <i class="fas fa-book-open text-orange-500 mr-2"></i>Jelajahi Menu Kami
                </h1>
                <p class="text-lg text-gray-600 max-w-xl mx-auto">Temukan beragam hidangan lezat yang kami tawarkan, dari
                    menu tradisional hingga kreasi spesial.</p>
                <div class="mt-4 h-1.5 w-24 bg-orange-500 mx-auto rounded-full"></div>
            </div>

            @if ($categories->isEmpty())
                <div class="text-center py-10 col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-20 w-20 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                    <h3 class="mt-3 text-xl font-semibold text-gray-700">Menu Belum Tersedia</h3>
                    <p class="mt-1 text-md text-gray-500">Saat ini kami belum memiliki menu untuk ditampilkan. <br>Silakan
                        kunjungi kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                    <div class="mt-8">
                        <a href="{{ route('contact.index') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <i class="fas fa-phone-alt mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            @else
                @foreach ($categories as $category)
                    <section class="mb-12 md:mb-16">
                        <div class="flex items-center mb-6 md:mb-8">
                            {{-- Anda bisa menambahkan logika untuk ikon kategori yang berbeda-beda jika mau --}}
                            <span class="p-3 bg-orange-100 text-orange-600 rounded-lg mr-4 text-2xl">
                                <i class="fas {{ $category->icon_class ?? 'fa-utensils' }} fa-fw"></i> {{-- Fallback ke fa-utensils --}}
                            </span>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 border-b-3 border-orange-400 pb-2">
                                {{ $category->name }}
                            </h2>
                        </div>

                        @if ($category->menuItems->isEmpty())
                            <p class="text-gray-500 ps-16">Belum ada item untuk kategori '{{ $category->name }}' saat ini.
                            </p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
                                @foreach ($category->menuItems as $item)
                                    <div
                                        class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-xl hover:-translate-y-1.5">
                                        <div class="relative h-52 sm:h-56 overflow-hidden">
                                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/400x300.png?text=' . urlencode($item->name) }}"
                                                alt="{{ $item->name }}"
                                                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                                            @if ($item->is_featured)
                                                <div
                                                    class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2.5 py-1 rounded-md shadow">
                                                    <i class="fas fa-star fa-xs mr-1"></i>UNGGULAN
                                                </div>
                                            @endif
                                            {{-- <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/50 to-transparent">
                                             <h3 class="text-md font-semibold text-white group-hover:text-orange-300 transition-colors" style="text-shadow: 1px 1px 1px rgba(0,0,0,0.7);">{{ $item->name }}</h3>
                                        </div> --}}
                                        </div>
                                        <div class="p-5 flex flex-col flex-grow">
                                            <h3
                                                class="text-lg font-semibold text-gray-800 mb-1.5 group-hover:text-orange-600 transition-colors">
                                                {{ $item->name }}</h3>
                                            {{-- Kategori bisa dihilangkan jika sudah ada di judul section kategori --}}
                                            {{-- @if ($item->menuCategory)
                                            <p class="text-xs text-gray-400 mb-2 uppercase tracking-wider">{{ $item->menuCategory->name }}</p>
                                        @endif --}}
                                            <p class="text-gray-600 text-xs mb-3 flex-grow leading-relaxed">
                                                {{ Str::limit($item->description, 70) }}</p>
                                            <div class="mt-auto pt-3 border-t border-gray-100">
                                                <div class="flex justify-between items-center">
                                                    <p class="text-lg font-bold text-orange-500">
                                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                                    </p>
                                                    <a href="{{ route('order.create') }}?add-item={{ $item->id }}"
                                                        class="inline-flex items-center justify-center px-4 py-2 rounded-md text-xs font-semibold text-white 
                                                          bg-orange-500 hover:bg-orange-600 active:bg-orange-700
                                                          focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1 
                                                          transition-all duration-300 transform hover:scale-105">
                                                        <i class="fas fa-cart-plus fa-sm mr-1.5"></i>Pesan
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endforeach
            @endif
        </div>
    </div>
@endsection
