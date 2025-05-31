@extends('public.layouts.app')

@section('title', 'Kontak Kami')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800"><i class="fas fa-headset mr-2 text-orange-500"></i> Hubungi Kami
                </h1>
                <p class="mt-2 text-lg text-gray-600">Kami siap membantu Anda. Jangan ragu untuk menghubungi kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 md:gap-12 max-w-5xl mx-auto">
                {{-- Kolom Informasi Kontak (md:col-span-2) --}}
                <div class="md:col-span-2 bg-white p-6 sm:p-8 rounded-xl shadow-xl space-y-6">
                    <h3 class="text-2xl font-semibold text-orange-600 mb-6 border-b pb-3">
                        <i class="fas fa-address-book mr-2"></i>Informasi Kontak
                    </h3>

                    @if (isset($siteSettings['address']) && !empty(trim($siteSettings['address'])))
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-xl">
                                <i class="fas fa-map-marker-alt fa-fw"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-gray-800">Alamat Kantor:</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $siteSettings['address'] }}</p>
                            </div>
                        </div>
                    @endif

                    @if (isset($siteSettings['contact_whatsapp']) && !empty(trim($siteSettings['contact_whatsapp'])))
                        @php
                            $wa_number_cleaned = preg_replace('/[^0-9]/', '', $siteSettings['contact_whatsapp']);
                            if (substr($wa_number_cleaned, 0, 1) === '0') {
                                $wa_number_cleaned = '62' . substr($wa_number_cleaned, 1);
                            }
                        @endphp
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center text-xl">
                                <i class="fab fa-whatsapp fa-fw"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-gray-800">WhatsApp:</h4>
                                <a href="https://wa.me/{{ $wa_number_cleaned }}" target="_blank"
                                    class="text-sm text-green-600 hover:text-green-700 hover:underline">
                                    {{ $siteSettings['contact_whatsapp'] }} (Chat Langsung)
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (isset($siteSettings['contact_email']) && !empty(trim($siteSettings['contact_email'])))
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xl">
                                <i class="fas fa-envelope fa-fw"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-gray-800">Email:</h4>
                                <a href="mailto:{{ $siteSettings['contact_email'] }}"
                                    class="text-sm text-red-600 hover:text-red-700 hover:underline">
                                    {{ $siteSettings['contact_email'] }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (isset($siteSettings['operating_hours']) && !empty(trim($siteSettings['operating_hours'])))
                        <div class="flex items-start pt-4 border-t mt-6">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-gray-100 text-gray-600 rounded-lg flex items-center justify-center text-xl shadow">
                                <i class="fas fa-clock fa-fw"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-md font-semibold text-gray-800 mb-0.5">Jam Operasional:</h4>
                                <div class="text-sm text-gray-600 leading-snug">
                                    @php
                                        $operatingLines = array_filter(
                                            explode("\n", trim($siteSettings['operating_hours'])),
                                        );
                                    @endphp
                                    @foreach ($operatingLines as $line)
                                        <p>{{ $line }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kolom Form Kontak (md:col-span-3) --}}
                <div class="md:col-span-3 bg-white p-6 sm:p-8 rounded-xl shadow-xl">
                    <h3 class="text-2xl font-semibold text-orange-600 mb-6 border-b pb-3">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan Langsung
                    </h3>

                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                            class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md"
                            role="alert">
                            <p class="font-bold">Pesan Terkirim!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 7000)"
                            class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Oops!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    @endif


                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        {{-- Nama Lengkap --}}
                        @php
                            $nameInputClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            if ($errors->has('name')) {
                                $nameInputClasses[] = 'border-red-500';
                            } else {
                                $nameInputClasses[] = 'border-gray-300';
                            }
                        @endphp
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="{{ implode(' ', $nameInputClasses) }}" placeholder="Nama Anda">
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat Email --}}
                        @php
                            $emailInputClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            if ($errors->has('email')) {
                                $emailInputClasses[] = 'border-red-500';
                            } else {
                                $emailInputClasses[] = 'border-gray-300';
                            }
                        @endphp
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="{{ implode(' ', $emailInputClasses) }}" placeholder="email@anda.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pesan Anda --}}
                        @php
                            $messageTextareaClasses = [
                                'block',
                                'w-full',
                                'px-4',
                                'py-2.5',
                                'border',
                                'rounded-lg',
                                'shadow-sm',
                                'focus:outline-none',
                                'focus:ring-2',
                                'focus:ring-orange-500',
                                'focus:border-orange-500',
                                'sm:text-sm',
                            ];
                            if ($errors->has('message')) {
                                $messageTextareaClasses[] = 'border-red-500';
                            } else {
                                $messageTextareaClasses[] = 'border-gray-300';
                            }
                        @endphp
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan Anda</label>
                            <textarea name="message" id="message" rows="5" required class="{{ implode(' ', $messageTextareaClasses) }}"
                                placeholder="Tuliskan pertanyaan atau kebutuhan Anda di sini...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-turnstile::widget />
                        @error('cf-turnstile-response')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        <div>
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 bg-orange-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Peta Lokasi --}}
            @if (isset($siteSettings['Maps_url']) && !empty(trim($siteSettings['Maps_url'])))
                <div class="mt-12 md:mt-16 bg-white p-6 sm:p-8 rounded-xl shadow-xl">
                    <h3 class="text-2xl font-semibold text-orange-600 mb-6 text-center md:text-left border-b pb-3">
                        <i class="fas fa-map-marked-alt mr-2"></i> Kunjungi Lokasi Kami
                    </h3>
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-md">
                        <iframe src="{{ $siteSettings['Maps_url'] }}" width="100%" height="450" style="border:0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script tambahan jika diperlukan
    </script>
@endpush
