@extends('public.layouts.app')

@section('title', 'Kontak Kami')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800">📞 Hubungi Kami</h1>
                <p class="mt-2 text-lg text-gray-600">Kami siap membantu Anda. Jangan ragu untuk menghubungi kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold text-orange-600 mb-6">Informasi Kontak</h2>
                    <div class="space-y-4 text-gray-700">
                        @if (isset($settings['address']) && !empty(trim($settings['address'])))
                            <p>
                                <strong class="font-medium">Alamat:</strong><br>
                                {{ $settings['address'] }}
                            </p>
                        @endif

                        {{-- Menampilkan Nomor WhatsApp --}}
                        @if (isset($settings['contact_whatsapp']) && !empty(trim($settings['contact_whatsapp'])))
                            <p>
                                <strong class="font-medium">No. WhatsApp:</strong><br>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']) }}"
                                    target="_blank"
                                    class="text-green-600 hover:text-green-700 underline inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-brand-whatsapp inline-block h-5 w-5 mr-1"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                        <path
                                            d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                    </svg>
                                    {{ $settings['contact_whatsapp'] }}
                                </a>
                            </p>
                        @endif

                        @if (isset($settings['contact_email']) && !empty(trim($settings['contact_email'])))
                            <p>
                                <strong class="font-medium">Email:</strong><br>
                                <a href="mailto:{{ $settings['contact_email'] }}"
                                    class="text-orange-500 hover:text-orange-700 underline">{{ $settings['contact_email'] }}</a>
                            </p>
                        @endif
                    </div>

                    {{-- Peta Lokasi menggunakan settings['Maps_url'] --}}
                    @if (isset($settings['Maps_url']) && !empty(trim($settings['Maps_url'])))
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-orange-600 mb-4">Lokasi Kami</h3>
                            <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-md">
                                <iframe src="{{ $settings['Maps_url'] }}" width="100%" height="350" style="border:0;"
                                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    @else
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-orange-600 mb-4">Lokasi Kami</h3>
                            <p class="text-gray-600">Peta lokasi akan segera tersedia.</p>
                        </div>
                    @endif
                </div>

                {{-- Form Kontak Tetap Sama --}}
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold text-orange-600 mb-6">Kirim Pesan</h2>
                    {{-- ... (sisa form kontak) ... --}}
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Berhasil!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Oops!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Pesan Anda</label>
                            <textarea name="message" id="message" rows="4" required
                                class="mt-1 block w-full px-3 py-2 border {{ $errors->has('message') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script tambahan jika diperlukan
    </script>
@endpush
