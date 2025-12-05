<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PenyewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(1)->create();

        User::create([
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
    }
}
