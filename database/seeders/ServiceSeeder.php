<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // Hati-hati: truncate hanya untuk dev environment
        // Service::truncate();

        // 1. Cari Cabang VILLA (Asumsi cuma ada satu cabang villa di Parepare)
        $villa = Cabang::where('kategori_cabang', 'villa')->first();

        if($villa) {
            Service::create([
                'id_cabang'   => $villa->id_cabang,
                'name' => 'Kolam Renang',
                'description' => 'Akses kolam renang privat selama menginap',
                'price' => 150000,
            ]);

            Service::create([
                'id_cabang'   => $villa->id_cabang,
                'name' => 'Gazebo',
                'description' => 'Sewa gazebo untuk acara keluarga',
                'price' => 100000,
            ]);

            Service::create([
                'id_cabang'   => $villa->id_cabang,
                'name' => 'Sound System',
                'description' => 'Sound system lengkap untuk acara',
                'price' => 200000,
            ]);
        } else {
            // Info di console kalau Villa tidak ditemukan
            $this->command->info('Skip ServiceSeeder: Cabang kategori Villa belum dibuat.');
        }
    }
}
