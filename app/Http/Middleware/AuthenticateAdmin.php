<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login DAN adalah admin
        if (!Auth::check()) {
            // Jika belum login sama sekali, arahkan ke halaman login publik
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->is_admin) {
            // Jika sudah login TAPI bukan admin, arahkan ke halaman dashboard publik atau beranda
            // dan berikan pesan error.
            // Anda bisa juga logout pengguna tersebut atau tampilkan halaman 'unauthorized' (403).
            // Auth::logout(); // Opsional: logout jika non-admin mencoba akses
            return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman admin.');
        }

        // Jika lolos semua pengecekan (sudah login dan adalah admin), lanjutkan request
        return $next($request);
    }
}
