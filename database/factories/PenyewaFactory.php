<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PenyewaFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;
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
            'alamat' => $faker->sentence(2),
            'foto_profil' => $faker->sentence(2),
            'role' => 'penyewa'
        ];
    }
}
