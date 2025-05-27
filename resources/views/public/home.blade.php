@extends('public.layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="bg-orange-500 text-white py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Selamat Datang di
                {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? 'Catering Kami' }}!
            </h1>
            <p class="text-lg md:text-xl mb-8">
                {{ app(\App\Models\Setting::class)->where('key', 'site_description')->first()?->value ?? 'Menyediakan hidangan lezat untuk setiap acara spesial Anda.' }}
            </p>
            <a href="{{ route('menu.index') }}"
                class="bg-white text-orange-600 font-semibold py-3 px-8 rounded-lg hover:bg-orange-100 transition duration-300">
                Lihat Menu Kami
            </a>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Menu Unggulan Kami 🌟</h2>
            @if ($featuredItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($featuredItems as $item)
                        <div
                            class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                            <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://via.placeholder.com/400x300.png?text=Menu+Image' }}"
                                alt="{{ $item->name }}" class="w-full h-56 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $item->name }}</h3>
                                @if ($item->menuCategory)
                                    <span
                                        class="text-sm text-orange-500 bg-orange-100 px-2 py-1 rounded-full mb-2 inline-block">{{ $item->menuCategory->name }}</span>
                                @endif
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($item->description, 80) }}</p>
                                <p class="text-lg font-bold text-orange-600">Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                                <a href="#"
                                    class="mt-4 inline-block bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 transition duration-300">Pesan
                                    Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-600">Belum ada menu unggulan saat ini.</p>
            @endif
        </div>
    </section>

    <section class="bg-gray-200 py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Promosi Spesial 🎉</h2>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <p class="text-xl text-gray-700">{{ $promotionMessage }}</p>
                {{-- Anda bisa menambahkan link atau detail promosi lainnya di sini --}}
            </div>
        </div>
    </section>
@endsection
