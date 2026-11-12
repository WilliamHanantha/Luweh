<?php

namespace Database\Factories;

use App\Models\Restoran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Restoran>
 */
class RestoranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Restoran::class;

    public function definition(): array
    {
        return [
            'nama_resto' => fake()->company(),
            'alamat' => fake()->address(),
            'jarak' => fake()->randomFloat(2, 0.5, 5),
            'rating' => fake()->randomFloat(1, 3, 5),
            'gambar' => null,
        ];
    }
}
