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
    protected static $counter = 1;
    public function definition(): array
    {
        $faker = Faker\Factory::create('id_ID');

        $tipe_kamar = $faker->randomElement(['Ekonomis', 'Standar']);

        $noKamar = 'kmr-' . str_pad(self::$counter++, 3, '0', STR_PAD_LEFT);

        return [
            'no_kamar' => $noKamar,
            'tipe_kamar' => $tipe_kamar,
            'harga_kamar' => $faker->numberBetween('50000', '30000'),
            'deskripsi' => $faker->text(200),
            'status' => $faker->randomElement(['Tersedia', 'Dihuni']),
            'gambar' => null,
        ];
    }
}
