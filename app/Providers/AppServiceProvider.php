<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\SettingsComposer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

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
                'public.home',                        // Halaman beranda publik
                // Tambahkan view lain yang membutuhkan $siteSettings,
                // seperti halaman kontak, menu, about jika mereka menampilkan nama situs/logo dari settings
                'public.contact',
                'public.menu_list', // Ganti dengan nama view daftar menu Anda jika berbeda
                'public.about',
                'public.order_form',

                'admin.layouts.app',                  // Layout admin utama
                'admin.layouts.partials.header',      // Header admin
                'admin.layouts.partials.footer',      // Footer admin

                'auth.*',                             // Semua view autentikasi
                'public.dashboard.index',             // Dashboard pelanggan
                'public.dashboard.order_detail',      // Detail pesanan pelanggan

                'emails.contact.form-message',        // Template email kontak kustom Anda
                'vendor.notifications.email',         // Template email notifikasi default Laravel
                'vendor.mail.html.header',            // Komponen header email
                'vendor.mail.html.footer',            // Komponen footer email
                'app.Notifications.CustomVerifyEmailNotification',
                'app.Notifications.CustomResetPasswordNotification'
            ],
            SettingsComposer::class
        );

        Paginator::useBootstrapFive();
    }
}
