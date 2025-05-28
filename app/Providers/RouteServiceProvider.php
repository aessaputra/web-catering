<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit; // Tambahkan jika belum ada
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request; // Tambahkan jika belum ada
use Illuminate\Support\Facades\RateLimiter; // Tambahkan jika belum ada
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The path to your application's "admin home" route.
     *
     * Admin users are redirected here after authentication if trying to access guest routes.
     *
     * @var string
     */
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Di Laravel 11, method mapRouteGroups tidak lagi dipanggil secara otomatis dari sini.
        // Pendaftaran rute (web.php, admin.php, api.php) dilakukan di bootstrap/app.php
        // jadi bagian this->routes() biasanya tidak diperlukan lagi di sini.
        // $this->routes(function () {
        // Route::middleware('api')
        // ->prefix('api')
        // ->group(base_path('routes/api.php'));

        // Route::middleware('web')
        // ->group(base_path('routes/web.php'));
        // });
    }
}
