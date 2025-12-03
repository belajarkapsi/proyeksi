<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // Hati-hati: truncate hanya untuk dev environment
        // Service::truncate();
        $villa = Cabang::where('kategori_cabang', 'villa');
        if($villa) {
        Service::create([
            'name' => 'Kolam Renang',
            'description' => 'Akses kolam renang privat selama menginap',
            'price' => 150000,
        ]);

        Service::create([
            'name' => 'Gazebo',
            'description' => 'Sewa gazebo untuk acara keluarga',
            'price' => 100000,
        ]);

        Service::create([
            'name' => 'Sound System',
            'description' => 'Sound system lengkap untuk acara',
            'price' => 200000,
        ]);
    } else {
        $this->command->info('Skip ServiceSeeder: Cabang kategori villa belum dibuat.');
    }
    }
}
