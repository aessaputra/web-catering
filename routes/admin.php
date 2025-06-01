<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactMessageController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Rute-rute ini akan otomatis di-prefix dengan URL '/admin'
| dan nama rute 'admin.' dari konfigurasi di bootstrap/app.php.
|
*/

// Akan menjadi 'admin.dashboard'
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Akan menjadi 'admin.categories.index', 'admin.categories.create', dll.
Route::resource('categories', MenuCategoryController::class)->except(['show'])->names('categories');

// Akan menjadi 'admin.menu-items.index', 'admin.menu-items.create', dll.
Route::resource('menu-items', MenuItemController::class)->except(['show'])->names('menu-items');

// Order Routes
// Akan menjadi 'admin.orders.archived', 'admin.orders.restore', dll.
Route::get('orders/archived', [AdminOrderController::class, 'archived'])->name('orders.archived');
Route::post('orders/{order}/restore', [AdminOrderController::class, 'restore'])->name('orders.restore');
Route::delete('orders/{order}/force-delete', [AdminOrderController::class, 'forceDelete'])->name('orders.force-delete');
Route::resource('orders', AdminOrderController::class)
  ->only(['index', 'show', 'update', 'destroy'])
  ->parameters(['orders' => 'order']) // Nama parameter sudah 'order'
  ->names('orders'); // Akan menjadi 'admin.orders.index', dll.

// Customer Routes
// Akan menjadi 'admin.customers.archived', 'admin.customers.restore', dll.
Route::get('customers/archived', [CustomerController::class, 'archived'])->name('customers.archived');
Route::post('customers/{customer}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
Route::delete('customers/{customer}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');
Route::resource('customers', CustomerController::class)
  ->only(['index', 'show', 'destroy'])
  ->parameters(['customers' => 'customer'])
  ->names('customers'); // Akan menjadi 'admin.customers.index', dll. (HAPUS 'admin.' dari sini)


// Settings Routes
// Akan menjadi 'admin.settings.general.index', dll.
Route::get('settings/general', [SettingController::class, 'generalSettingsIndex'])->name('settings.general.index');
Route::post('settings/general', [SettingController::class, 'storeGeneralSettings'])->name('settings.general.store');

// Akan menjadi 'admin.settings.about.index', dll.
Route::get('settings/about-page', [SettingController::class, 'aboutPageSettingsIndex'])->name('settings.about.index');
Route::post('settings/about-page', [SettingController::class, 'storeAboutPageSettings'])->name('settings.about.store');

// Contact Messages Routes
// Akan menjadi 'admin.contact-messages.index', dll.
Route::resource('contact-messages', ContactMessageController::class)
  ->only(['index', 'show', 'destroy'])
  ->parameters(['contact_messages' => 'contactMessage']) // Nama parameter sudah 'contactMessage'
  ->names('contact-messages');
