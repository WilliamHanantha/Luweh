<?php

namespace Database\Factories;

use App\Models\DaftarMakanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DaftarMakanan>
 */
class DaftarMakananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_makanan' => $this->faker->name(),
            'deskripsi' => $this->faker->sentence(),
            'harga' => $this->faker->randomFloat(2, 5000, 100000),
            'stok' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean(),
        ];
    }
}
