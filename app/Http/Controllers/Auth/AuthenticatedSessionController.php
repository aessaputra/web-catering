<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorAuthenticationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        if ($user && $user->hasRole('admin')) {
            $twoFactorCode = random_int(100000, 999999);
            $twoFactorExpiresAt = now()->addMinutes(config('auth.2fa_code_lifetime', 10));

            $userIdFor2FA = $user->id;
            $userEmailFor2FA = $user->email;

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $request->session()->put([
                '2fa_user_id_pending' => $userIdFor2FA,
                '2fa_code_hashed' => Hash::make((string)$twoFactorCode),
                '2fa_expires_at' => $twoFactorExpiresAt,
                '2fa_code_sent_at' => now(),
                '2fa_attempts' => 0,
            ]);

            try {
                Mail::to($userEmailFor2FA)->send(new TwoFactorAuthenticationCodeMail($twoFactorCode));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email kode 2FA untuk user ID ' . $userIdFor2FA . ': ' . $e->getMessage());
                $request->session()->forget(['2fa_user_id_pending', '2fa_code_hashed', '2fa_expires_at', '2fa_code_sent_at', '2fa_attempts']);
                return redirect()->route('login')->withErrors(['email' => 'Gagal mengirim kode verifikasi. Silakan coba lagi atau hubungi administrator.']);
            }

            return redirect()->route('2fa.verify.form');
        }

        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
