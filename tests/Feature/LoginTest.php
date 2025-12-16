<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\LoginController;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Definisikan routenya terlebih dahulu
        Route::get('/admin/dashboard', function() { return 'Halaman Admin'; })
            ->middleware('web')
            ->name('admin.dashboard');

        Route::get('/dashboard', function() { return 'Halaman Penyewa'; })
            ->middleware('web')
            ->name('dashboard');

        Route::post('/login', [ LoginController::class, 'authenticate' ])
            ->middleware('web')
            ->name('login');

        // Route Logout
        Route::post('/logout', [ LoginController::class, 'destroy' ])
            ->middleware('web')
            ->name('logout');
    }

    // skenario 1: pemilik berhasil login
    public function test_pemilik_bisa_login_dengan_username()
    {
        $pemilik = Pemilik::create([
            'nama_lengkap' => 'Admin Penginapan',
            'email' => 'admin@contoh.com',
            'username' => 'admin123',
            'password' => 'password123',
            'no_telp' => '08123456789',
            'role' => 'admin'
        ]);

        // Coba Login
        $response = $this->post(route('login'), [
            'username' => 'admin123',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::guard('pemilik')->check());
        $this->assertFalse(Auth::guard('web')->check());
        $response->assertSessionHasNoErrors();
    }

    // Skenario 2: Penyewa bisa login dengan email
    public function test_penyewa_bisa_login_dengan_email()
    {
        // Buat data dulu
        User::create([
            'nama_lengkap'  => 'Penyewa Bijak',
            'no_telp'       => '08976543281',
            'email'         => 'penyewa@gmail.com',
            'username'      => 'penyewa123',
            'password'      => 'rahasia123',
            'role'          => 'penyewa',
        ]);

        // Coba login
        $response = $this->post(route('login'), [
            'username' => 'penyewa@gmail.com',
            'password' => 'rahasia123',
        ]);

        // Assert
        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('pemilik')->check());
        $response->assertSessionHasNoErrors();
    }

    // Skenario3: login gagal karena password salah
    public function test_login_gagal_karena_password_salah()
    {
        // Buat data dulu
        User::create([
            'nama_lengkap'  => 'Penyewa Bijak',
            'no_telp'       => '08976543281',
            'email'         => 'penyewa@gmail.com',
            'username'      => 'penyewa123',
            'password'      => 'rahasia123',
            'role'          => 'penyewa',
        ]);

        // Coba login
        $response = $this->post(route('login'), [
            'username' => 'penyewa123',
            'password' => 'bukanrahasia',
        ]);

        // Assert
        $response->assertStatus(302);
        $this->assertFalse(Auth::guard('pemilik')->check());
        $response->assertSessionHasErrors([
            'username' => 'Username/Email atau Password Salah!'
        ]);
    }

    // Skenario 4: Login gagal karena akun belum ada
    public function test_login_gagal_karena_akun_belum_ada()
    {
        // Coba login
        $response = $this->post(route('login'), [
            'username' => 'penyewa123',
            'password' => 'bukanrahasia',
        ]);

        // Assert
        $this->assertFalse(Auth::guard('pemilik')->check());
        $this->assertFalse(Auth::guard('web')->check());
        $response->assertSessionHasErrors([
            'username' => 'Username/Password tidak ditemukan! Akun belum terdaftar.'
        ]);
    }

    // Skenario 5: Bisa Logout
    public function test_bisa_logout()
    {
        $user = User::create([
            'nama_lengkap'  => 'Penyewa Bijak',
            'no_telp'       => '08976543281',
            'email'         => 'penyewa@gmail.com',
            'username'      => 'penyewa123',
            'password'      => 'rahasia123',
            'role'          => 'penyewa',
        ]);

        // Login manual
        Auth::guard('web')->login($user);
        $this->assertTrue(Auth::guard('web')->check());

        // Logout
        $response = $this->post(route('logout'));

        // Assert
        $response->assertRedirect(route('dashboard'));
        $this->assertFalse(Auth::guard('pemilik')->check());
        $this->assertFalse(Auth::guard('web')->check());
    }

    // Skenario 6: Validasi input kosong
    public function test_login_gagal_karena_kredensial_kosong()
    {
        $response = $this->post(route('login'), [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['username', 'password']);
    }
}
