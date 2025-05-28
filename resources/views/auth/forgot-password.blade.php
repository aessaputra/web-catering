@extends('public.layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
                <div class="mb-6 text-center">
                    <a href="{{ route('home') }}" class="inline-block mb-4">
                        <span class="text-3xl font-bold text-orange-600">
                            🍰
                            {{ app(\App\Models\Setting::class)->where('key', 'site_name')->first()?->value ?? config('app.name', 'Catering Lezat') }}
                        </span>
                    </a>
                    <h2 class="text-2xl font-semibold text-gray-800">Lupa Password?</h2>
                </div>

                <div class="mb-4 text-sm text-gray-600 text-center">
                    {{ __('Masukkan alamat email Anda dan kami akan mengirimkan link untuk mereset password Anda.') }}
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

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
                    <div class="mb-6">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Alamat Email') }}</label>
                        <input id="email" class="{{ implode(' ', $emailInputClasses) }}" type="email" name="email"
                            value="{{ old('email') }}" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kirim Link Reset Password') }}
                        </button>
                    </div>
                    <div class="text-center mt-6">
                        <a class="underline text-sm text-gray-600 hover:text-orange-500" href="{{ route('login') }}">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
