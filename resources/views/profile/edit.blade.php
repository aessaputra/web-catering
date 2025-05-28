@extends('public.layouts.app')

@section('title', 'Edit Profil Saya') {{-- Judul halaman --}}

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Notifikasi Sukses dari Update Profil (jika ada) --}}
            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="mb-4 rounded-md bg-green-50 p-4 text-sm font-medium text-green-600">
                    {{ __('Profil berhasil diperbarui.') }}</div>
            @endif
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="mb-4 rounded-md bg-green-50 p-4 text-sm font-medium text-green-600">
                    {{ __('Password berhasil diperbarui.') }}</div>
            @endif


            <div class="p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-lg rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
