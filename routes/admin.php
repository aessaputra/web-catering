<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;

// Rute Admin akan di-prefix dengan '/admin' dan diberi nama 'admin.'
// serta dilindungi oleh middleware 'auth' dan 'admin' dari bootstrap/app.php

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('categories', MenuCategoryController::class)->except(['show'])->names('categories');
Route::resource('menu-items', MenuItemController::class)->except(['show'])->names('menu-items');
Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update'])->names('orders');
Route::resource('customers', CustomerController::class)->only(['index', 'show'])->names('customers');
Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

// Rute untuk Pesan Kontak
Route::get('contact-messages', [SettingController::class, 'contactMessagesIndex'])->name('contact-messages.index');
Route::get('contact-messages/{message}', [SettingController::class, 'showContactMessage'])->name('contact-messages.show')->whereNumber('message');
Route::delete('contact-messages/{message}', [SettingController::class, 'destroyContactMessage'])->name('contact-messages.destroy')->whereNumber('message');

// Contoh Rute Login Admin Khusus (jika diperlukan nanti)
// Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
// Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');