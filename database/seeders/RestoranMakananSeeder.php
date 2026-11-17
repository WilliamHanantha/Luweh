<?php

namespace Database\Seeders;

use App\Models\DaftarMakanan;
use App\Models\Restoran;
use Illuminate\Database\Seeder;

class RestoranMakananSeeder extends Seeder
{
    public function run(): void
    {
        $restoranIds = Restoran::orderBy('id')->pluck('id')->values();

        if ($restoranIds->isEmpty()) {
            return;
        }

        DaftarMakanan::orderByDesc('status')->orderBy('id')->get()
            ->each(function ($makanan, $i) use ($restoranIds) {
                DaftarMakanan::where('id', $makanan->id)
                    ->update(['restoran_id' => $restoranIds[$i % $restoranIds->count()]]);
            });
    }
}
