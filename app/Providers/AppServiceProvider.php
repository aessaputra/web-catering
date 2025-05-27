<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\Schema; // Contoh: Jika Anda butuh ini nanti
// use Illuminate\Pagination\Paginator; // Contoh: Jika Anda butuh ini nanti

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Contoh: Schema::defaultStringLength(191);
        // Contoh: Paginator::useBootstrapFive();

        // Jika RateLimiter memang ingin Anda definisikan secara global di sini, tidak masalah,
        // tapi pastikan tidak ada konflik `use` statement.
        // Kode RateLimiter dari file Anda sebelumnya:
        // use Illuminate\Cache\RateLimiting\Limit;
        // use Illuminate\Http\Request;
        // use Illuminate\Support\Facades\RateLimiter;
        //
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });
    }
}