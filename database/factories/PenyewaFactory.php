<?php

namespace Database\Factories;

use App\Models\Penyewa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penyewa>
 */
class PenyewaFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = Penyewa::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = Faker\Factory::create('id_ID');
        return [
            'nama_lengkap' => fake()->name(),
            'no_telp' => $faker->randomNumber(8, true),
            'email' => $faker->freeEmail(),
            'username' => 'penyewa',
            'password' => static::$password ??= Hash::make('penyewa'),
        ];
    }
}
