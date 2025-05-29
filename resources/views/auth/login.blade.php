@extends('public.layouts.app')

@section('title', 'Login Pelanggan')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Login Pelanggan</h2>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email Address --}}
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
                            value="{{ old('email') }}" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
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
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                        <input id="password" class="{{ implode(' ', $passwordInputClasses) }}" type="password"
                            name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="block mt-4 mb-5">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 focus:ring-offset-0"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-700">{{ __('Ingat saya') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-orange-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500"
                                href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Log in') }}
                        </button>
                    </div>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? <a href="{{ route('register') }}"
                                class="font-medium text-orange-600 hover:text-orange-500 underline">Daftar di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
