@extends('public.layouts.app')

@section('title', 'Verifikasi Alamat Email Anda')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
                <div class="mb-6 text-center">
                    <a href="{{ route('home') }}" class="inline-block mb-4">
                        <span class="text-3xl font-bold text-orange-600">
                            🍰
                            {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Lezat') }}
                        </span>
                    </a>
                    <h2 class="text-2xl font-semibold text-gray-800">Verifikasi Email Anda</h2>
                </div>

                <div class="mb-4 text-sm text-gray-600 leading-relaxed">
                    {{ __('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan ke email Anda? Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang lain.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md">
                        {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
                    </div>
                @endif

                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kirim Ulang Email Verifikasi') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto underline text-sm text-gray-600 hover:text-orange-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500">
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
