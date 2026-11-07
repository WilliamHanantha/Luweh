<?php

namespace Database\Seeders;

use App\Models\Restoran;
use Illuminate\Database\Seeder;

class RestoranSeeder extends Seeder
{
    public function run(): void
    {
        $restorans = [
            ['nama_resto' => 'Sinar Djaya Resto', 'alamat' => 'Jl. Mulyosari No. 25', 'jarak' => 1.2, 'rating' => 4.8],
            ['nama_resto' => 'Royal Kitchen', 'alamat' => 'Jl. Keputih Tegal Timur No. 1', 'jarak' => 1.6, 'rating' => 4.6],
            ['nama_resto' => 'Nasi Goreng Bu Ida', 'alamat' => 'Jl. Arif Rahman Hakim No. 10', 'jarak' => 0.8, 'rating' => 4.7],
            ['nama_resto' => 'Warung Sederhana', 'alamat' => 'Jl. Raya ITS No. 5', 'jarak' => 0.5, 'rating' => 4.4],
        ];

        foreach ($restorans as $resto) {
            Restoran::firstOrCreate(['nama_resto' => $resto['nama_resto']], $resto);
        }
    }
}
