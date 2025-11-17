<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Kamar;
use Illuminate\Database\Seeder;
use Faker;

class CabangDanKamarSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker\Factory::create('id_ID');

        // Buat cabang dengan Eloquent supaya dapat ID-nya
        $cabangs = collect([
            Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => $faker->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
                'gambar_cabang' => 'dummy.jpg',
            ]),

            Cabang::create([
                'nama_cabang' => 'The Green Villa Parepare',
                'deskripsi' => $faker->text(300),
                'jumlah_kamar' => 2,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'villa',
                'gambar_cabang' => 'dummy.jpg',
            ]),

            Cabang::create([
                'nama_cabang' => 'Pondok Satu',
                'deskripsi' => $faker->text(300),
                'jumlah_kamar' => 14,
                'lokasi' => 'Pangkep',
                'kategori_cabang' => 'kost',
                'gambar_cabang' => 'dummy.jpg',
            ])
        ]);

        // Buat kamar untuk setiap cabang
        $cabangs->each(function ($cabang) {
            Kamar::factory()->count($cabang->jumlah_kamar)->create([
                'id_cabang' => $cabang->id_cabang ?? $cabang->id
            ]);
        });
    }
}