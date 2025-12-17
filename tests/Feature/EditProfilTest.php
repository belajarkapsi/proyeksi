<?php

namespace Tests\Feature;

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditProfilTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Siapkan rute palsu untuk edit profilnya
        Route::get('/profile/edit', [ProfileController::class, 'edit'])
            ->middleware('web')->name('profile.edit');

        Route::put('/profile/update', [ProfileController::class, 'update'])
            ->middleware('web')->name('profile.update');
    }

    // Siapkan data default user yang tersimpan
    private function loginUser($attributes = [])
    {
        $userDefault = [
            'nama_lengkap'  => 'Siti Hajar',
            'no_telp'       => '08123456789',
            'email'         => 'hajar@test.com',
            'username'      => 'user_hajar',
            'password'      => 'password123',
            'tanggal_lahir' => '2004-12-11',
            'usia'          => 21,
            'jenis_kelamin' => 'Perempuan',
            'alamat'        => 'Jl. Agussalim',
            'asal'          => 'Parepare',
            'role'          => 'penyewa',
        ];

        // Timpa variabel attributes ke data default user
        $timpaData = array_merge($userDefault, $attributes);

        $user = User::create($timpaData);
        $this->actingAs($user);

        return $user;
    }

    // Skenario 1: Halaman edit profil bisa diakses penyewa
    public function test_penyewa_bisa_mengakses_halaman_edit_profil()
    {
        $this->loginUser();

        // Memastikan user bisa masuk ke halaman edit profil dan melihat datanya
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200);

        $response->assertSee('Siti Hajar');
    }

    // Skenario 2: Perbarui profil dengan data valid
    public function test_update_profil_dengan_data_valid()
    {
        $user = $this->loginUser();

        $inputData = [
            'nama_lengkap'  => 'Abdul Khalik',
            'no_telp'       => '08512342314',
            'username'      => 'khalik123',
            'tanggal_lahir' => '2005-11-12',
            'usia'          => 20,
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Jl. Pemuda',
            'asal'          => 'Jakarta',
        ];

        $response = $this->patch(route('profile.update'), $inputData);
        $response->assertRedirect(route('profile.edit'));

        // Assert
        $this->assertTrue($user->username == 'khalik123');
        $this->assertEquals('Jl. Pemuda', $user->alamat);
        $this->assertNotEquals(21, $user->usia);
    }

    // Skenario 3: Logika perbarui foto profil
    public function test_ubah_foto_profil()
    {
        Storage::fake('public');

        // Setup data user dengan foto lama
        $fotoLama = UploadedFile::fake()->image('fotolama.jpg');
        $pathLama = $fotoLama->store('profile-photos', 'public');
        $user = $this->loginUser(['foto_profil' => $pathLama]);
        // Cek file ada
        $this->assertFileExists(Storage::disk('public')->path($pathLama));

        // User upload foto baru
        $fotoBaru = UploadedFile::fake()->image('fotobaru.jpg');
        $pathBaru = 'profile-photos/' . $fotoBaru->hashName();
        $dataBaru = [
            'nama_lengkap'  => 'Abdul Khalik',
            'no_telp'       => '08512342314',
            'username'      => 'khalik123',
            'tanggal_lahir' => '2005-11-12',
            'usia'          => 20,
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Jl. Pemuda',
            'asal'          => 'Jakarta',
            'foto_profil'   => $fotoBaru
        ];
        // Proses update
        $this->patch(route('profile.update'), $dataBaru);

        // Assert
        $user->refresh();
        $this->assertFalse($pathLama == $user->foto_profil);
        $this->assertFileDoesNotExist(Storage::disk('public')->path($pathLama));
        $this->assertFileExists(Storage::disk('public')->path($pathBaru));
    }
}
