<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Definisikan route yang dibutuhkan
        Route::post('/register', [RegisterController::class, 'store'])
            ->middleware('web')
            ->name('register.store');
        Route::get('/login', function () {
            return 'Halaman Login';
        })->middleware('web')->name('login');
    }

    // Skenario 1: Registrasi berhasil dengan data valid
    public function test_registrasi_dengan_data_valid()
    {
        // Siapkan data yang ingin diinput
        $inputData = [
            'nama_lengkap'  => 'Badrul Atok',
            'no_telp'       => '081234567890',
            'email'         => 'badrul@contoh.com',
            'username'      => 'badrul123',
            'password'      => 'password123',
            'password_confirmation'      => 'password123',
        ];

        // Coba registrasi
        $response = $this->post(route('register.store'), $inputData);

        // Assert
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
        $this->assertDatabaseHas('penyewa', [
            'email' => 'badrul@contoh.com',
            'username' => 'badrul123',
            'nama_lengkap' => 'Badrul Atok',
        ]);

        $user = User::where('username', 'badrul123')->first();
        $this->assertEquals(Hash::check('password123', $user->password), $user->password);
        $this->assertNotEquals('password123', $user->password);
    }

    // Skenario 2: Registrasi gagal dengan data tidak valid
    public function test_registari_gagal_karena_data_kosong()
    {
        // Registrasi dengan data kosong
        $response = $this->post(route('register.store'), []);

        // Pastikan error untuk field yang wajib diisi
        $response->assertSessionHasErrors([
            'nama_lengkap',
            'no_telp',
            'email',
            'username',
            'password'
        ]);
    }

    // Skenario 3: Registari gagal karena password tidak cocok
    public function test_registrasi_gagal_karena_password_tidak_cocok()
    {
        // Siapkan data yang ingin diinput
        $inputData = [
            'nama_lengkap'  => 'Badrul Atok',
            'no_telp'       => '081234567890',
            'email'         => 'badrul@contoh.com',
            'username'      => 'badrul123',
            'password'      => 'password123',
            'password_confirmation'      => 'bedapassword',
        ];

        // Coba registasi
        $response = $this->post(route('register.store'), $inputData);

        // Assert cek pesan error
        $response->assertSessionHasErrors([
            'password' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.'
        ]);
    }

    // Skenario 4: Registrasi gagal karena email/username sudah digunakan
    public function test_registrasi_gagal_jika_username_duplikat()
    {
        // Buat user yang tersimpan langsung kedalam database
        User::create([
            'nama_lengkap'  => 'Badrul Atok',
            'no_telp'       => '081234567890',
            'email'         => 'badrul@contoh.com',
            'username'      => 'badrul123',
            'password'      => 'password123',
            'role'          => 'penyewa',
        ]);

        // Coba daftar dengan data sama lagi
        $inputData = [
            'nama_lengkap'  => 'User Baru',
            'no_telp'       => '085123467890',
            'email'         => 'badrul@contoh.com',
            'username'      => 'badrul123',
            'password'      => 'passwordpernah',
            'password_confirmation'      => 'passwordpernah',
        ];
        $response = $this->post(route('register.store'), $inputData);

        // Assert pesan errornya
        $response->assertSessionHasErrors([
            'email' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'username' => 'Username ini sudah ada yang pakai, coba yang lain.'
        ]);
    }
}
