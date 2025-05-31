<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class TwoFactorChallengeController extends Controller
{
    protected function throttleKey(Request $request, int $userId): string
    {
        return Str::transliterate(Str::lower('2fa_verify|' . $userId . '|' . $request->ip()));
    }

    public function showChallengeForm(Request $request)
    {
        if (!$request->session()->has('2fa_user_id_pending')) {
            return redirect()->route('login');
        }
        return view('auth.two-factor-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id_pending');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['code' => 'Sesi verifikasi tidak valid. Silakan login kembali.']);
        }

        $throttleKey = $this->throttleKey($request, $userId);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'code' => trans('auth.throttle_2fa', ['seconds' => $seconds]),
            ]);
        }

        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $storedCodeHashed = $request->session()->get('2fa_code_hashed');
        $expiresAt = $request->session()->get('2fa_expires_at');

        if (!$storedCodeHashed || !$expiresAt) {
            RateLimiter::hit($throttleKey);
            return redirect()->route('login')->withErrors(['code' => 'Sesi verifikasi tidak valid atau telah kedaluwarsa.']);
        }

        if (now()->gt($expiresAt)) {
            $this->clearTwoFactorSession($request);
            RateLimiter::clear($throttleKey);
            return redirect()->route('login')->withErrors(['code' => 'Kode verifikasi telah kedaluwarsa. Silakan login kembali.']);
        }

        $isValidCode = Hash::check((string) $request->code, $storedCodeHashed);

        if (!$isValidCode) {
            RateLimiter::hit($throttleKey);
            $attempts = $request->session()->get('2fa_attempts', 0) + 1;
            $request->session()->put('2fa_attempts', $attempts);
            if ($attempts >= 3) {
                $this->clearTwoFactorSession($request);
                RateLimiter::clear($throttleKey);
                return redirect()->route('login')->withErrors(['code' => 'Terlalu banyak percobaan kode yang salah. Sesi verifikasi direset. Silakan login kembali.']);
            }
            return back()->withErrors(['code' => 'Kode verifikasi yang Anda masukkan salah.']);
        }

        RateLimiter::clear($throttleKey);
        $user = User::find($userId);

        if (!$user || !$user->hasRole('admin')) {
            $this->clearTwoFactorSession($request);
            return redirect()->route('login')->withErrors(['code' => 'Autentikasi pengguna gagal.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $this->clearTwoFactorSession($request);
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
    }

    protected function clearTwoFactorSession(Request $request)
    {
        $request->session()->forget(['2fa_user_id_pending', '2fa_code_hashed', '2fa_expires_at', '2fa_code_sent_at', '2fa_attempts']);
    }
}
