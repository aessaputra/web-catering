@extends('public.layouts.app') {{-- Menggunakan layout publik Anda --}}

@section('title', 'Reset Password Anda') {{-- Judul halaman --}}

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
                    <h2 class="text-2xl font-semibold text-gray-800">Atur Ulang Password Anda</h2>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    <div class="mb-4">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                        <input id="email" class="{{ implode(' ', $emailInputClasses) }}" type="email" name="email"
                            value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    @php
                        $passwordInputClasses = [
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
                        if ($errors->has('password')) {
                            $passwordInputClasses[] = 'border-red-500';
                        } else {
                            $passwordInputClasses[] = 'border-gray-300';
                        }
                    @endphp
                    <div class="mb-4">
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password Baru') }}</label>
                        <input id="password" class="{{ implode(' ', $passwordInputClasses) }}" type="password"
                            name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    @php
                        $confirmPasswordClasses = [
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
                        if ($errors->has('password_confirmation')) {
                            // Langsung cek error 'password_confirmation'
                            $confirmPasswordClasses[] = 'border-red-500';
                        } else {
                            $confirmPasswordClasses[] = 'border-gray-300';
                        }
                    @endphp
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Konfirmasi Password Baru') }}</label>
                        <input id="password_confirmation" class="{{ implode(' ', $confirmPasswordClasses) }}"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
