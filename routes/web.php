<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OrderController as PublicOrderController;
use App\Http\Controllers\Public\UserDashboardController; // Pastikan ini sudah di-import

// ... (rute publik lainnya seperti /, /menu, dll. tetap sama) ...
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::get('/pemesanan', [PublicOrderController::class, 'create'])->name('order.create');
Route::post('/pemesanan', [PublicOrderController::class, 'store'])->name('order.store');


// Rute untuk Autentikasi dan Dashboard Pengguna
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute /dashboard akan ditangani oleh UserDashboardController
    // UserDashboardController akan memiliki logika untuk redirect admin
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard'); //INI PERUBAHAN PENTING

    Route::get('/dashboard/orders/{order}', [UserDashboardController::class, 'showOrder'])
        ->name('dashboard.orders.show')
        ->whereNumber('order');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
