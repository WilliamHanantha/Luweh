<?php

namespace Database\Factories;

use App\Models\PenukaranPoin;
use App\Models\ProdukMitra;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenukaranPoin>
 */
class PenukaranPoinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PenukaranPoin::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state([
                'role' => 'courier',
            ]),
            'produk_mitra_id' => ProdukMitra::factory(),
            'jumlah_poin_digunakan' => 50,
        ];
    }
}
