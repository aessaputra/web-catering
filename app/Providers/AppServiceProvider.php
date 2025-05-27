<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Untuk pengguna biasa (sesuai Breeze default)

    /**
     * The path to your application's "admin home" route.
     *
     * Admin users are redirected here after authentication if trying to access guest routes.
     *
     * @var string
     */
    public const ADMIN_HOME = '/admin/dashboard'; // Untuk admin

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Konfigurasi rate limiter bisa dibiarkan default
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Tidak perlu lagi mendefinisikan rute di sini untuk Laravel 11,
        // karena sudah dihandle oleh bootstrap/app.php ->withRouting()
    }
}
