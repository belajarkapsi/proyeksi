<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;

use function Symfony\Component\Clock\now;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker\Factory::create('id_ID');

        DB::table('cabang')->insert([
            [
                'nama_cabang' => 'Pondok Siti Hajar (Kost Parepare)',
                'deskripsi' => $faker->text(),
                'jumlah_kamar' => '20',
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
                'gambar_cabang' => fake()->image(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_cabang' => 'The Green Villa Parepare',
                'deskripsi' => $faker->text(),
                'jumlah_kamar' => '2',
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'villa',
                'gambar_cabang' => fake()->image(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_cabang' => 'Pondok Siti Hajar (Kost Pangkep)',
                'deskripsi' => $faker->text(),
                'jumlah_kamar' => '10',
                'lokasi' => 'Pangkep',
                'kategori_cabang' => 'kost',
                'gambar_cabang' => fake()->image(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
