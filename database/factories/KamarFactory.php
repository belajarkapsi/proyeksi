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

        $tipe_kamar = $faker->randomElement(['Ekonomis', 'Standar']);

        return [
            'no_kamar' => $faker->numerify('kmr-###'),
            'tipe_kamar' => $tipe_kamar,
            'harga_kamar' => $faker->randomNumber(5, true),
            'deskripsi' => $faker->text(200),
            'status' => $faker->randomElement(['Tersedia', 'Dihuni']),
            'slug' => 'kamar-' . Str::slug($tipe_kamar),
        ];
    }
}
