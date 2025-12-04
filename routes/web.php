<?php

use App\Http\Controllers\Admin\DataPengelolaController;
use App\Http\Controllers\Admin\DataPenyewaController;
use App\Http\Controllers\Admin\DataKamarController;
use App\Http\Controllers\Admin\InformasiCabangController;
use App\Http\Controllers\Admin\LayananVillaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfileController;
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

    Route::get('/cabang/{lokasi}/{kategori}/villa/detail-villa', [CabangController::class, 'detailVilla'])
        ->name('cabang.villa.detail');
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
    ->middleware('auth:web,pemilik')
    ->name('logout');
    // Antisipasi ketika langsung cari /logout:
Route::get('logout', function() {
    return redirect()->route('dashboard');
});


// Route Booking
Route::middleware(['auth:web', 'lengkapi.profil'])->group(function() {
    // Halaman pilih kamar + checkout (GET dari card / POST dari form)
    Route::match(['get', 'post'],'/booking', [BookingController::class, 'checkout'])
        ->name('booking.checkout');

    // Proses Simpan ke Database (Action dari form checkout)
    Route::post('/booking/store', [BookingController::class, 'store'])
        ->name('booking.store');

    // Riwayat pesanan user
    Route::get('/pesanan/riwayat-pesanan', [BookingController::class, 'history'])
        ->name('booking.riwayat');

    Route::middleware(['user.sebenarnya'])->group(function () {
        // Halaman detail pesanan + countdown + tombol batalkan
        Route::get('/pesanan/riwayat-pesanan/detail-pesanan/{id_pemesanan}', [BookingController::class, 'payment'])
            ->name('booking.pembayaran');

        // Route Batal Pesanan (dipanggil dari tombol "Batalkan Pesanan")
        Route::post('/booking/cancel/{id_pemesanan}', [BookingController::class, 'cancel'])
            ->name('booking.batal');
    });
});

// Route khusus AJAX Cek Status
Route::get('/booking/check-status/{id_pemesanan}', [BookingController::class, 'checkStatus'])
    ->name('booking.check_status');


// Route Admin
Route::middleware(['auth:pemilik'])->group(function() {
    Route::prefix('admin')->group(function() {
        // Route Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'index'])
                ->name('admin.dashboard');

        // Data Penyewa
        Route::resource('penyewa', DataPenyewaController::class);
        // Data Pengelola
        Route::resource('pengelola', DataPengelolaController::class);
        // Data Kamar
        Route::prefix('{lokasi}')->name('admin.cabang')->group(function() {
            Route::resource('kamar', DataKamarController::class);

            // Data Layanan Villa
            Route::resource('layanan-villa', LayananVillaController::class);
            // Data Informasi Cabang
            Route::resource('informasi-cabang', InformasiCabangController::class);
        });

        // Route Profil Admin
        Route::get('/profil', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::patch('/profil', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    });

});
