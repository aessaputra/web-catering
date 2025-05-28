<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OrderController as PublicOrderController;
use App\Http\Controllers\Public\UserDashboardController;

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

// Rute Publik Kustom Kita
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::get('/pemesanan', [PublicOrderController::class, 'create'])->name('order.create');
Route::post('/pemesanan', [PublicOrderController::class, 'store'])->name('order.store');


// Rute untuk Autentikasi dan Dashboard Pengguna (Breeze & Kustom)
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute /dashboard sekarang akan ditangani oleh UserDashboardController
    // Pengecekan is_admin untuk redirect ke admin.dashboard bisa ditangani oleh
    // middleware AuthenticateAdmin untuk rute /admin/*, atau bisa juga
    // ditambahkan di UserDashboardController jika ada logika khusus.
    // Untuk sekarang, middleware 'auth' dan 'verified' sudah cukup untuk /dashboard pelanggan.
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Rute untuk melihat detail pesanan pelanggan
    Route::get('/dashboard/orders/{order}', [UserDashboardController::class, 'showOrder'])
        ->name('dashboard.orders.show')
        ->whereNumber('order'); // Memastikan {order} adalah angka (ID)

    // Rute profil bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat rute autentikasi Breeze (login, register, forgot password, dll.)
require __DIR__ . '/auth.php';
