<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->validateCsrfTokens(except: [
            'notification/handling',
        ]);

       $middleware->trustProxies(
            '*',
            Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO
        );

        // Daftarkan alias 'admin' di sini
        $middleware->alias([
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Ini biasanya sudah ada dari Breeze
            'guest'    => \App\Http\Middleware\RedirectIfAuthenticated::class, // Ini biasanya sudah ada dari Breeze
            'admin'    => \App\Http\Middleware\AuthenticateAdmin::class, // <--- TAMBAHKAN BARIS INI
            // alias middleware lain jika ada
        ]);

        // Anda mungkin juga ingin memastikan middleware 'auth' dari Laravel (untuk session guard)
        // atau 'auth.session' diterapkan sebelum 'admin' di grup rute Anda jika ada masalah.
        // Namun, dengan grup `['web', 'auth', 'admin']`, urutan penanganan harusnya sudah benar.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
