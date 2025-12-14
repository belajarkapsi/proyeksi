<?php

namespace Tests\Feature;

use App\Models\Cabang;
use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class PemesananTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Setup jalur untuk redirect setelah pemesanan berhasil
        Route::get('/pesanan/riwayat-pesanan/detail-pesanan/{id_pemesanan}', function ($id_pemesanan) {
            return 'Halaman Pembayaran: ' . $id_pemesanan;
        })->middleware('web')->name('booking.pembayaran');

        Route::get('/login', function () {
            return 'Halaman Login';
        })->name('login');
    }

    // Skenario 1: Penyewa bisa memesan kamar
    public function test_penyewa_bisa_memesan_kamar()
    {
        $cabang = Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => fake()->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
        ]);

        $kamar = Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '111',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 100000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
        ]);

        $user = User::create([
            'nama_lengkap' => 'Abriel Yosua',
            'no_telp' => '081234567892',
            'email' => 'iniemail@gmail.com',
            'username' => 'abrielyosua',
            'password' => 'abrielyosua',
            'tanggal_lahir' => '2005-11-11',
            'usia' => '20',
            'asal' => 'Makassar',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Balaikota No. 1',
            'foto_profil' => fake()->sentence(2),
            'role' => 'penyewa',
            'remember_token' => Str::random(10),
        ]);

        $inputData = [
            'nama_lengkap' => $user->nama_lengkap,
            'telepon'      => $user->no_telp,
            'email'        => $user->email,
            'check_in'     => Carbon::tomorrow()->format('Y-m-d'),
            'kamar_ids'    => [$kamar->id_kamar],
            'durasi'       => [$kamar->id_kamar => 2],
        ];

        // Kirim data
        $response = $this->actingAs($user)->post(route('booking.store'), $inputData);

        // Assert
        $pemesanan = Pemesanan::first();
        $this->assertNotNull($pemesanan);
        $this->assertEquals('PS00001', $pemesanan->id_pemesanan);
        $response->assertRedirect(route('booking.pembayaran', $pemesanan->id_pemesanan));
    }

    // Skenario 2: Memesan villa dengan tambahan layanan
    public function test_pesan_villa_dengan_layanan()
    {
        // Buat data yang dibutuhin dulu
        $cabang = Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => fake()->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
        ]);
        $kamar = Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '111',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 100000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
        ]);
        $layanan = Service::create([
            'id_cabang' => $cabang->id_cabang,
            'name' => 'Kolam Renang',
            'description' => 'Kolam Mantappu Jiwa',
            'price' => 20000,
        ]);
        $user = User::create([
            'nama_lengkap' => 'Abriel Yosua',
            'no_telp' => '081234567892',
            'email' => 'iniemail@gmail.com',
            'username' => 'abrielyosua',
            'password' => 'abrielyosua',
            'tanggal_lahir' => '2005-11-11',
            'usia' => '20',
            'asal' => 'Makassar',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Balaikota No. 1',
            'foto_profil' => fake()->sentence(2),
            'role' => 'penyewa',
            'remember_token' => Str::random(10),
        ]);

        // Input data pemesanannya
        $inputData = [
            'nama_lengkap' => $user->nama_lengkap,
            'telepon' => $user->no_telp,
            'email' => $user->email,
            'check_in' => now()->format('Y-m-d'),
            'kamar_ids' => [$kamar->id_kamar],
            'durasi' => [$kamar->id_kamar => 1],

            // Input untuk services
            'services' => [$layanan->id],
            'service_quantity' => [$layanan->id => 2] // Pesan 2 layanan
        ];
        $this->actingAs($user)->post(route('booking.store'), $inputData);

        // Assert
        $pemesanan = Pemesanan::first();
        $this->assertEquals(140000, $pemesanan->total_harga);
        $this->assertNotNull($pemesanan);
    }

    // Skenario 3: user tidak bisa pesan kalau belum login
    public function test_user_tidak_bisa_pesan_kalau_belum_login()
    {
        $response = $this->post(route('booking.store'), []);
        $response->assertRedirect(route('login'));
        $this->assertEmpty(Pemesanan::all());
    }
}
