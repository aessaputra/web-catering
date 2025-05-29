<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;
use Illuminate\Support\Facades\Schema;
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
        // Jika Anda menggunakan defaultStringLength:
        // Schema::defaultStringLength(191);

        // Mendaftarkan SettingsComposer ke view-view tertentu atau semua view
        // Kita akan mendaftarkannya ke view yang kemungkinan besar membutuhkan settings global
        View::composer(
            [
                'public.layouts.app',                 // Layout publik utama
                'public.partials.header',             // Header publik
                'public.partials.footer',             // Footer publik
                'admin.layouts.app',                  // Layout admin utama (untuk nama situs di title/header admin)
                'admin.layouts.partials.header',      // Header admin
                'admin.layouts.partials.footer',      // Footer admin
                'auth.*',                             // Semua view autentikasi (login, register, dll.)
                'public.dashboard.index',             // Dashboard pelanggan
                'public.dashboard.order_detail',      // Detail pesanan pelanggan
                'emails.contact.form-message',        // Template email kontak ke admin
                'vendor.notifications.email',         // Template email notifikasi default Laravel
                'vendor.mail.html.header',            // Komponen header email
                'vendor.mail.html.footer',            // Komponen footer email
                // Tambahkan view lain yang membutuhkan $siteSettings
            ],
            SettingsComposer::class
        );

        // Alternatif jika ingin data settings tersedia di SEMUA view:
        // View::composer('*', SettingsComposer::class);
        // Untuk saat ini, mendaftarkan ke view spesifik lebih terkontrol.
    }
}
