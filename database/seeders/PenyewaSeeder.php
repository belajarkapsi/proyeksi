<?php

namespace Database\Seeders;

use App\Models\Penyewa;
use Illuminate\Database\Seeder;

class PenyewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penyewa::factory(1)->create();
    }
}
