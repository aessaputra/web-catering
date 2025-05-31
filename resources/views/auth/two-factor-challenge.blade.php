@extends('public.layouts.app')

@section('title', 'Verifikasi Dua Langkah')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Verifikasi Dua Langkah</h2>
                    <p class="text-sm text-gray-600 mt-2">
                        Kami telah mengirimkan kode 6 digit ke alamat email Anda. Masukkan kode tersebut di bawah ini untuk
                        melanjutkan.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf

                    @php
                        $codeInputClasses = [
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
                            'text-center',
                            'tracking-[0.5em]',
                            'font-mono',
                            'placeholder-gray-400',
                        ];
                        if ($errors->has('code')) {
                            $codeInputClasses[] = 'border-red-500';
                        } else {
                            $codeInputClasses[] = 'border-gray-300';
                        }
                    @endphp
                    <div class="mb-4">
                        <label for="code"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Kode Verifikasi') }}</label>
                        <input id="code" class="{{ implode(' ', $codeInputClasses) }}" type="text" name="code"
                            required autofocus autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                            maxlength="6" placeholder="------">
                        <x-input-error :messages="$errors->get('code')" class="mt-1" />
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Verifikasi & Lanjutkan
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-gray-600 hover:text-orange-500 underline focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500 rounded-md">
                            Bukan Anda atau ingin coba login ulang? Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
