<?php

namespace Database\Factories;

use App\Models\Cabang;
use Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kamar>
 */
class KamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker\Factory::create('id_ID');
        return [
            'no_kamar' => $faker->numerify('kmr-###'),
            'tipe_kamar' => $faker->sentence(1),
            'harga_kamar' => $faker->randomNumber(5, true),
            'deskripsi' => $faker->text(200),
            'status' => 'Tersedia',
            'slug' => Str::slug($faker->sentence())

        ];
    }
}
