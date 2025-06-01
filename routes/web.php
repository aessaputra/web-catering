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
use App\Http\Controllers\Public\PaymentCallbackController;
use App\Http\Controllers\Public\MidtransNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute Publik Kustom
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

// Rute Pemesanan dan Pembayaran
Route::get('/pemesanan', [PublicOrderController::class, 'create'])->name('order.create');
Route::post('/pemesanan', [PublicOrderController::class, 'store'])->name('order.store');
Route::get('/payment/checkout/{orderId}', [PublicOrderController::class, 'showPaymentPage'])->name('payment.checkout'); // orderId agar lebih jelas

// Callback dan Notifikasi Pembayaran
Route::get('/payment/finish', [PaymentCallbackController::class, 'handleFinish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentCallbackController::class, 'handleUnfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentCallbackController::class, 'handleError'])->name('payment.error');
Route::post('/notification/handling', [MidtransNotificationController::class, 'handle'])->name('midtrans.notification.handler');


// Rute Autentikasi Bawaan Breeze (Login, Register, Lupa Password, Verifikasi Email)
require __DIR__ . '/auth.php'; // Ini akan memuat rute dari routes/auth.php

// Rute yang memerlukan pengguna untuk login TETAPI TIDAK HARUS verifikasi email
Route::middleware(['auth', 'web'])->group(function () { // Pastikan 'web' middleware group diterapkan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk Verifikasi Dua Langkah (2FA) - harus bisa diakses setelah login tahap 1
Route::middleware(['web'])->group(function () { // Hanya 'web' karena user belum sepenuhnya login
    Route::get('/2fa/verify', [TwoFactorChallengeController::class, 'showChallengeForm'])->name('2fa.verify.form');
    Route::post('/2fa/verify', [TwoFactorChallengeController::class, 'verifyChallenge'])->name('2fa.verify');
});


// Rute yang memerlukan pengguna untuk login DAN SUDAH verifikasi email
Route::middleware(['auth', 'verified', 'web'])->group(function () { // Pastikan 'web' middleware group diterapkan
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders/{order}', [UserDashboardController::class, 'showOrder'])
        ->name('dashboard.orders.show')
        ->whereNumber('order');
    // Tambahkan rute lain di sini yang memerlukan email terverifikasi
});
