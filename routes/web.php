<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // Dari Breeze
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OrderController as PublicOrderController;

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


// Rute default dari Breeze (biarkan atau sesuaikan jika perlu)
Route::get('/dashboard', function () {
    // Cek jika user adalah admin, arahkan ke admin dashboard
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    // Jika bukan admin, tampilkan dashboard pelanggan
    return view('dashboard'); // View dashboard pelanggan dari Breeze
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';