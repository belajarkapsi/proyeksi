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
            'email'         => 'pengelola2@sipestar.com',
            'username'      => 'abdulkhalik',
            'password'      => 'abdulkhalik',
            'tanggal_lahir' => fake()->date('Y-m-d'),
            'usia'          => fake()->randomNumber(2, true),
            'jenis_kelamin' => 'Laki-laki',
            'role'          => 'pengelola',
            'id_cabang'     => 2,
            'created_at'    => now(),
        ]);

        Pengelola::create([
            'nama_lengkap'  => 'Khalik Hartono',
            'no_telp'       => $faker->phoneNumber,
            'email'         => 'pengelola1@sipestar.com',
            'username'      => 'khalikhartono',
            'password'      => 'khalikhartono',
            'tanggal_lahir' => fake()->date('Y-m-d'),
            'usia'          => fake()->randomNumber(2, true),
            'jenis_kelamin' => 'Perempuan',
            'role'          => 'pengelola',
            'id_cabang'     => 1,
            'created_at'    => now(),
        ]);

        Pengelola::create([
            'nama_lengkap'  => 'Abdul Hartono',
            'no_telp'       => $faker->phoneNumber,
            'email'         => 'pengelola3@sipestar.com',
            'username'      => 'abdulhartono',
            'password'      => 'abdulhartono',
            'tanggal_lahir' => fake()->date('Y-m-d'),
            'usia'          => fake()->randomNumber(2, true),
            'jenis_kelamin' => 'Laki-laki',
            'role'          => 'pengelola',
            'id_cabang'     => 3,
            'created_at'    => now(),
        ]);
    }
}
