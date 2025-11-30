<?php

namespace Database\Seeders;

use App\Models\Pemilik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker;

class PemilikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker\Factory::create('id_ID');

        Pemilik::create([
            'nama_lengkap'  => 'Siti Hajar',
            'username'      => 'admin123',
            'email'         => 'admin@sipestar.com',
            'password'      => 'password123',
            'no_telp'       => $faker->phoneNumber,
            'role'          => 'admin'
        ]);
    }
}
