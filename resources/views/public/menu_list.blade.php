@extends('public.layouts.app')

@section('title', 'Daftar Menu')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">📜 Daftar Menu Kami</h1>

        @if ($categories->isEmpty())
            <p class="text-center text-gray-600 text-lg">Saat ini belum ada menu yang tersedia. Silakan kunjungi kembali
                nanti.</p>
        @else
            @foreach ($categories as $category)
                <section class="mb-16">
                    <h2 class="text-3xl font-semibold text-orange-600 mb-8 border-b-2 border-orange-200 pb-2">
                        {{ $category->name }}
                    </h2>
                    @if ($category->menuItems->isEmpty())
                        <p class="text-gray-500">Belum ada item untuk kategori ini.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                            @foreach ($category->menuItems as $item)
                                <div
                                    class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col transform hover:scale-105 transition-transform duration-300">
                                    <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/400x300.png?text=' . urlencode($item->name) }}"
                                        alt="{{ $item->name }}" class="w-full h-56 object-cover">
                                    <div class="p-6 flex flex-col flex-grow">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $item->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-3 flex-grow">
                                            {{ Str::limit($item->description, 100) }}</p>
                                        <p class="text-lg font-bold text-orange-600 mb-4">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                        {{-- Tombol Tambah ke Keranjang akan diimplementasikan nanti --}}
                                        <a href="#"
                                            class="mt-auto block w-full text-center bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 transition duration-300">
                                            Tambah ke Pesanan
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endforeach
        @endif
    </div>
@endsection
