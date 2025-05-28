<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) { // Jika user sudah login
                $user = Auth::user();
                if ($user->hasRole('admin')) {
                    // Jika admin, arahkan ke dashboard admin
                    return redirect(RouteServiceProvider::ADMIN_HOME); // ADMIN_HOME biasanya '/admin/dashboard'
                } else {
                    // Jika pelanggan biasa, arahkan ke dashboard pelanggan
                    return redirect(RouteServiceProvider::HOME); // HOME biasanya '/dashboard'
                }
            }
        }

        return $next($request);
    }
}
