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
        $menus = [
            [
                'nama_makanan' => 'Nasi Goreng',
                'image' => 'assets/images/nasi-goreng.png',
                'deskripsi' => 'Weight (per item): 260 gr',
                'harga' => 10000,
                'stok' => 12,
                'status' => false, // Nonaktif (sesuai screenshot mockup)
            ],
            [
                'nama_makanan' => 'Ayam Geprek Sambal Korek',
                'image' => 'assets/images/ayam-geprek.svg',
                'deskripsi' => 'Weight (per item): 300 gr',
                'harga' => 15000,
                'stok' => 8,
                'status' => false, // Nonaktif
            ],
            [
                'nama_makanan' => 'Mie Goreng Spesial',
                'image' => 'assets/images/mie-goreng.svg',
                'deskripsi' => 'Weight (per item): 240 gr',
                'harga' => 12000,
                'stok' => 10,
                'status' => false, // Nonaktif
            ],
            [
                'nama_makanan' => 'Soto Ayam Lamongan',
                'image' => 'assets/images/soto-ayam.svg',
                'deskripsi' => 'Weight (per item): 350 gr',
                'harga' => 14000,
                'stok' => 6,
                'status' => true, // Aktif
            ],
            [
                'nama_makanan' => 'Sate Ayam Madura',
                'image' => 'assets/images/sate-ayam.svg',
                'deskripsi' => 'Weight (per item): 200 gr',
                'harga' => 18000,
                'stok' => 15,
                'status' => true, // Aktif
            ],
        ];

        foreach ($menus as $menu) {
            DaftarMakanan::updateOrCreate(
                ['nama_makanan' => $menu['nama_makanan']],
                $menu
            );
        }
    }
}
