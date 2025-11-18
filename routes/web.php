<?php

use App\Http\Controllers\CabangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Route Cabang
Route::get('/cabang/{lokasi}/{kategori}', [CabangController::class, 'show'])
    ->middleware('validasi.cabang')
    ->name('cabang.show');

// Route untuk user belum login
Route::middleware('guest.only')->group(function() {
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
    
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

// Route untuk Pesan Kamar


// Route Logout
Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');