<?php

namespace Database\Factories;

use App\Models\Penyewa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penyewa>
 */
class PenyewaFactory extends Factory
{

    protected $model = Penyewa::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => 'penyewa',
            'password' => 'penyewa',
        ];
    }
}
