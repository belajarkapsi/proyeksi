<?php

use App\Http\Controllers\CabangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\ProfileController;

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

// Route Cabang Kamar Berdasarkan Tipe
Route::get('/cabang/{lokasi}/{kategori}/{slug?}', [CabangController::class, 'type'])
    ->middleware('validasi.cabang')
    ->name('cabang.type');

// Route untuk user belum login
Route::middleware('guest.only')->group(function() {
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
    
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

// Route untuk Detail Kamar
Route::get('/kamar/detail-kamar/{no_kamar}', [KamarController::class, 'show'])->name('kamar.show');


// Route untuk Profil
Route::middleware('auth')->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});



// Route Logout
Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

























//Route Booking
Route::get('/kamar/detail-kamar/{no_kamar}', function () {
    return view('kamar/booking');
});