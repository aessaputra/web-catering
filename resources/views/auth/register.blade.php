@extends('public.layouts.app')

@section('title', 'Registrasi Pelanggan Baru')

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
                    <h2 class="text-2xl font-semibold text-gray-800">Registrasi Akun Baru</h2>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name --}}
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
                    <div class="mb-4">
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nama Lengkap') }}</label>
                        <input id="name" class="{{ implode(' ', $nameInputClasses) }}" type="text" name="name"
                            value="{{ old('name') }}" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

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
                            value="{{ old('email') }}" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Phone Number (Jika Anda mengaktifkannya) --}}
                    {{-- @php
                    $phoneInputClasses = ['block', 'w-full', 'px-4', 'py-2.5', 'border', 'rounded-lg', 'shadow-sm', 'focus:outline-none', 'focus:ring-2', 'focus:ring-orange-500', 'focus:border-orange-500', 'sm:text-sm'];
                    if ($errors->has('phone')) { $phoneInputClasses[] = 'border-red-500'; } else { $phoneInputClasses[] = 'border-gray-300'; }
                @endphp
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nomor Telepon') }}</label>
                    <input id="phone" class="{{ implode(' ', $phoneInputClasses) }}" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div> --}}

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
                            name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Confirm Password --}}
                    @php
                        $passwordConfirmationInputClasses = [
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
                            $passwordConfirmationInputClasses[] = 'border-red-500';
                        } else {
                            $passwordConfirmationInputClasses[] = 'border-gray-300';
                        }
                    @endphp
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('Konfirmasi Password') }}</label>
                        <input id="password_confirmation" class="{{ implode(' ', $passwordConfirmationInputClasses) }}"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-end mb-6">
                        <a class="underline text-sm text-gray-600 hover:text-orange-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-orange-500"
                            href="{{ route('login') }}">
                            {{ __('Sudah punya akun?') }}
                        </a>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full ms-4 inline-flex items-center justify-center px-4 py-2.5 bg-orange-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
