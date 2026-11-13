<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kontribusi;
use App\Models\ProdukMitra;
use App\Models\Restoran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RedeemPointsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or retrieve demo courier
        $courier = User::firstOrCreate(
            ['email' => 'demo.courier@luweh.test'],
            [
                'name' => 'Demo Courier',
                'password' => Hash::make('password'),
                'role' => 'courier',
            ]
        );

        // 2. Create or retrieve demo customer
        $customer = User::firstOrCreate(
            ['email' => 'demo.customer@luweh.test'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 3. Create contribution records
        Kontribusi::firstOrCreate(
            ['user_id' => $courier->id],
            ['total_point' => 150]
        );

        Kontribusi::firstOrCreate(
            ['user_id' => $customer->id],
            ['total_point' => 150]
        );

        // 4. Create partner products
        $products = [
            [
                'nama_produk' => 'Butter Croissant',
                'partner' => 'Over The Moon Cafe',
                'deskripsi' => 'Flaky Butter Croissant reward from Over The Moon Cafe.',
                'poin_dibutuhkan' => 50,
                'stok' => 10,
            ],
            [
                'nama_produk' => 'Sei ayam platter',
                'partner' => 'Sei Nusantara',
                'deskripsi' => 'Meal voucher reward from Sei Nusantara.',
                'poin_dibutuhkan' => 100,
                'stok' => 10,
            ],
            [
                'nama_produk' => 'Everything sandwich',
                'partner' => 'Subway Serve',
                'deskripsi' => 'Grocery package reward from Subway Serve.',
                'poin_dibutuhkan' => 200,
                'stok' => 10,
            ],
        ];

        foreach ($products as $product) {
            $restoran = Restoran::firstOrCreate(
                ['nama_resto' => $product['partner']],
                [
                    'alamat' => 'Surabaya',
                    'jarak' => 1.0,
                    'rating' => 4.5,
                ]
            );

            ProdukMitra::firstOrCreate(
                ['nama_produk' => $product['nama_produk']],
                [
                    'restoran_id' => $restoran->id,
                    'deskripsi' => $product['deskripsi'],
                    'gambar' => null,
                    'poin_dibutuhkan' => $product['poin_dibutuhkan'],
                    'stok' => $product['stok'],
                    'status' => true,
                ]
            );
        }
    }
}
