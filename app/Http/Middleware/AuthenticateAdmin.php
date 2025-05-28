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
        if (!Auth::check()) {
            // Jika belum login sama sekali, arahkan ke halaman login publik
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ganti pengecekan is_admin dengan pengecekan role 'admin'
        // atau permission 'akses admin panel'
        if (!Auth::user()->hasRole('admin')) {
            // ATAU jika Anda ingin lebih spesifik dengan permission:
            // if (!Auth::user()->can('akses admin panel')) {
            // Jika sudah login TAPI bukan admin/tidak punya permission, arahkan ke halaman beranda publik
            return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses ke halaman admin.');
        }

        return $next($request);
    }
}
