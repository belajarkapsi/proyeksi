<?php

use App\Http\Controllers\CabangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;

// Route utama saat membuka sistem/aplikasi
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
// Redirect sama ke dashboard
Route::redirect('/dashboard', '/');


// Route Cabang
Route::middleware('validasi.cabang')->group(function() {
    // Dashboard Cabang
    Route::get('/cabang/{lokasi}/{kategori}', [CabangController::class, 'show'])
        ->name('cabang.show');

    // Daftar Kamar di Cabang Tertentu
    Route::get('/cabang/{lokasi}/{kategori}/kamar', [KamarController::class, 'index'])
        ->name('cabang.kamar.index');

    // Route detail kamar
    Route::get('/cabang/{lokasi}/{kategori}/kamar/{no_kamar}', [kamarController::class, 'show'])
        ->name('cabang.kamar.show');
});


// Route untuk user belum login
Route::middleware('guest.only')->group(function() {
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);

    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});


// Route untuk Profil
Route::middleware('auth')->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// Route Logout
Route::post('logout', [LoginController::class, 'destroy'])
->middleware('auth')
    ->name('logout');
    // Antisipasi ketika langsung cari /logout:
Route::get('logout', function() {
    return redirect()->route('dashboard');
});


// //Route Booking
Route::middleware(['auth', 'lengkapi.profil'])->group(function() {
    Route::match(['get', 'post'],'/booking', [BookingController::class, 'checkout'])->name('booking.checkout');

    // Proses Simpan ke Database (Action dari form checkout)
    Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
});
