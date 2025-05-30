<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;

// Rute Admin akan di-prefix dengan '/admin' dan diberi nama 'admin.'
// serta dilindungi oleh middleware 'auth' dan 'admin' dari bootstrap/app.php

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('categories', MenuCategoryController::class)->except(['show'])->names('categories');
Route::resource('menu-items', MenuItemController::class)->except(['show'])->names('menu-items');
Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update'])->names('orders');
Route::get('customers/archived', [CustomerController::class, 'archived'])->name('customers.archived');
Route::post('customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
Route::delete('customers/{customer}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');
Route::resource('customers', CustomerController::class)
  ->only(['index', 'show', 'destroy'])
  ->parameters(['customers' => 'customer'])
  ->names('customers');


// Pengaturan Umum & Branding
Route::get('settings/general', [SettingController::class, 'generalSettingsIndex'])->name('settings.general.index');
Route::post('settings/general', [SettingController::class, 'storeGeneralSettings'])->name('settings.general.store');

// Pengaturan Konten Halaman "Tentang Kami"
Route::get('settings/about-page', [SettingController::class, 'aboutPageSettingsIndex'])->name('settings.about.index');
Route::post('settings/about-page', [SettingController::class, 'storeAboutPageSettings'])->name('settings.about.store');

// Rute untuk Pesan Kontak
Route::resource('contact-messages', ContactMessageController::class)
  ->only(['index', 'show', 'destroy'])
  ->parameters(['contact-messages' => 'contactMessage'])
  ->names('contact-messages');
