<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OrderController as PublicOrderController;
use App\Http\Controllers\Public\UserDashboardController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;

// Rute Publik Kustom Kita
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::get('/pemesanan', [PublicOrderController::class, 'create'])->name('order.create');
Route::post('/pemesanan', [PublicOrderController::class, 'store'])->name('order.store');

// Rute yang memerlukan pengguna untuk login TETAPI TIDAK HARUS verifikasi email
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk Verifikasi Dua Langkah (2FA)
Route::get('/2fa/verify', [TwoFactorChallengeController::class, 'showChallengeForm'])->name('2fa.verify.form');
Route::post('/2fa/verify', [TwoFactorChallengeController::class, 'verifyChallenge'])->name('2fa.verify');

// Rute yang memerlukan pengguna untuk login DAN SUDAH verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders/{order}', [UserDashboardController::class, 'showOrder'])
        ->name('dashboard.orders.show')
        ->whereNumber('order');
    // Tambahkan rute lain di sini yang memerlukan email terverifikasi
});

require __DIR__ . '/auth.php';
