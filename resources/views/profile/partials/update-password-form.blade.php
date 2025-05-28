<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        @php
            $currentPasswordClasses = [
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
            if ($errors->updatePassword->has('current_password')) {
                $currentPasswordClasses[] = 'border-red-500';
            } else {
                $currentPasswordClasses[] = 'border-gray-300';
            }
        @endphp
        <div class="mb-4">
            <label for="current_password"
                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password Saat Ini') }}</label>
            <input id="current_password" name="current_password" type="password"
                class="{{ implode(' ', $currentPasswordClasses) }}" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        {{-- New Password --}}
        @php
            $newPasswordClasses = [
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
            if ($errors->updatePassword->has('password')) {
                $newPasswordClasses[] = 'border-red-500';
            } else {
                $newPasswordClasses[] = 'border-gray-300';
            }
        @endphp
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password Baru') }}</label>
            <input id="password" name="password" type="password" class="{{ implode(' ', $newPasswordClasses) }}"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        {{-- Confirm New Password --}}
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
            // Tidak ada error spesifik untuk password_confirmation di $errors->updatePassword secara default,
            // biasanya error konfirmasi ada di bawah 'password'.
            // Namun, jika ada error 'password_confirmation', kita bisa tambahkan:
            // if ($errors->updatePassword->has('password_confirmation')) {
            //     $confirmPasswordClasses[] = 'border-red-500';
            // } else {
            //     $confirmPasswordClasses[] = 'border-gray-300';
            // }
            // Untuk amannya, kita samakan dengan newPasswordClasses jika ada error pada password (karena confirm terkait dengan itu)
            if ($errors->updatePassword->get('password') || $errors->updatePassword->get('password_confirmation')) {
                $confirmPasswordClasses[] = 'border-red-500';
            } else {
                $confirmPasswordClasses[] = 'border-gray-300';
            }
        @endphp
        <div class="mb-6">
            <label for="password_confirmation"
                class="block text-sm font-medium text-gray-700 mb-1">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="{{ implode(' ', $confirmPasswordClasses) }}" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" {{-- Durasi notifikasi sedikit lebih lama --}}
                    class="text-sm text-green-600 font-medium">{{ __('Password berhasil diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>
