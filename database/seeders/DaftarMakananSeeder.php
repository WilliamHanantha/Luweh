<?php

namespace Database\Seeders;

use App\Models\DaftarMakanan;
use Illuminate\Database\Seeder;

class DaftarMakananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DaftarMakanan::factory()->count(10)->create();
    }
}
