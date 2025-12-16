<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Pengelola;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;

class PengelolaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker\Factory::create('id_ID');

        Pengelola::create([
            'nama_lengkap'  => 'Abdul Khalik',
            'no_telp'       => $faker->phoneNumber,
            'email'         => 'pengelola@sipestar.com',
            'username'      => 'abdulkhalik',
            'password'      => 'abdulkhalik',
            'tanggal_lahir' => fake()->date('Y-m-d'),
            'usia'          => fake()->randomNumber(2, true),
            'jenis_kelamin' => 'Laki-laki',
            'role'          => 'pengelola',
            'id_cabang'     => 2,
            'created_at'    => now(),
        ]);
    }
}
