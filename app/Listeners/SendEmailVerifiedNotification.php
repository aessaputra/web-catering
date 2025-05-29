<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use RealRashid\SweetAlert\Facades\Alert;

class SendEmailVerifiedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        // User yang emailnya baru saja diverifikasi bisa diakses melalui $event->user
        // Kita akan flash pesan sukses yang akan ditangkap SweetAlert saat redirect
        if ($event->user) { // Pastikan user ada
            Alert::success('Verifikasi Berhasil!', 'Alamat email Anda telah berhasil diverifikasi.')
                ->toToast() // Tampilkan sebagai toast agar tidak terlalu mengganggu
                ->position('top-end');
        }
    }
}
