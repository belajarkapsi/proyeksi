<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'tanggal_lahir' => fake()->date(),
            'usia' => $faker->randomNumber(2, true),
            'asal' => $faker->timezone('ID'),
            'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => $faker->sentence(2),
            'foto_profil' => $faker->sentence(2),
            'role' => 'penyewa',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}
