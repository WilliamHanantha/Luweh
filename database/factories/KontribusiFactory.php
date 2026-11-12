<?php

namespace Database\Factories;

use App\Models\Kontribusi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kontribusi>
 */
class KontribusiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_point' => fake()->numberBetween(0, 500),
        ];
    }
}
