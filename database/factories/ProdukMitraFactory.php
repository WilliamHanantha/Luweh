<?php

namespace Database\Factories;

use App\Models\ProdukMitra;
use App\Models\Restoran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProdukMitra>
 */
class ProdukMitraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProdukMitra::class;
    public function definition(): array
    {
        return [
            'restoran_id' => Restoran::factory(),
            'nama_produk' => fake()->word(),
            'harga' => fake()->randomFloat(2, 10, 100),
            'stok' => fake()->numberBetween(0, 100),
            'deskripsi' => fake()->sentence(),
            'gambar' => null,
            'poin_dibutuhkan' => 50,
            'status' => true,
        ];
    }
}
