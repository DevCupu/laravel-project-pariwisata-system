<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\IsAdmin;

// Public routes
Route::get('/', fn() => view('home'));
Route::get('/beranda', [HomeController::class, 'index'])->name('home');
Route::get('/destinations', [DestinasiController::class, 'index'])->name('destinations');
Route::get('/destinations/{id}', [DestinasiController::class, 'show'])->name('destinations.show');
Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel');

// Admin routes
Route::middleware(['auth', IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Route::resource('/kategori', App\Http\Controllers\Admin\KategoriWisataController::class);
        Route::resource('/destinasi', App\Http\Controllers\Admin\DestinasiController::class);
        // Route::resource('/event', App\Http\Controllers\Admin\EventController::class);
        // Route::resource('/akomodasi', App\Http\Controllers\Admin\AkomodasiController::class);
        // Route::resource('/artikel', App\Http\Controllers\Admin\ArtikelController::class);
        // Route::resource('/galeri', App\Http\Controllers\Admin\GaleriController::class);
    });


// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = Auth::user();
    return redirect()->route(
        $user->role === 'admin' ? 'admin.dashboard' : 'home'
    );
})->middleware('auth')->name('dashboard');

require __DIR__ . '/auth.php';
