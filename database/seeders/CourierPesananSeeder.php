<?php

namespace Database\Seeders;

use App\Models\DaftarMakanan;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Restoran;
use Illuminate\Database\Seeder;

class CourierPesananSeeder extends Seeder
{
    /**
     * Seed 5 dummy pesanans untuk Food Hero & Activity Kurir
     */
    public function run(): void
    {
        // 5 Restoran dengan lokasi & gambar
        $restoransData = [
            [
                'nama_resto' => 'Nasi Goreng Bu Ida',
                'alamat'     => 'Jl. Mulyasari No. 25',
                'jarak'      => 0.8,
                'rating'     => 4.7,
                'gambar'     => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_resto' => 'Sinar Djaya Resto',
                'alamat'     => 'Jl. Raya Gubeng No. 45',
                'jarak'      => 1.2,
                'rating'     => 4.8,
                'gambar'     => 'assets/images/sinar-djaya.png',
            ],
            [
                'nama_resto' => 'Warung Pak Harto',
                'alamat'     => 'Jl. Semolowaru No. 10',
                'jarak'      => 1.5,
                'rating'     => 4.5,
                'gambar'     => 'assets/images/nasi-goreng-card.png',
            ],
            [
                'nama_resto' => 'Royal Kitchen',
                'alamat'     => 'Jl. Keputih Tegal Timur No. 1',
                'jarak'      => 1.6,
                'rating'     => 4.6,
                'gambar'     => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_resto' => 'Ayam Bakar Wong Solo',
                'alamat'     => 'Jl. Dharmawangsa No. 88',
                'jarak'      => 2.1,
                'rating'     => 4.9,
                'gambar'     => 'assets/images/nasi-goreng.png',
            ],
        ];

        $restoranMap = [];
        foreach ($restoransData as $r) {
            $resto = Restoran::firstOrCreate(
                ['nama_resto' => $r['nama_resto']],
                $r
            );
            $resto->update([
                'alamat' => $r['alamat'],
                'gambar' => $r['gambar'],
            ]);
            $restoranMap[$r['nama_resto']] = $resto;
        }

        // 5 Pesanan dummy untuk Food Hero
        $pesananDummy = [
            [
                'resto'             => 'Nasi Goreng Bu Ida',
                'nama_penerima'     => 'Ahmad Fauzi',
                'alamat_pengiriman' => 'Jl. Kepuh Tegalsari Timur No. 1',
                'gambar_makanan'    => 'assets/images/nasi-goreng.png',
                'status'            => 'on-going',
                'status_courier'    => 'on-going',
                'metode_pengiriman' => 'Delivery',
                'ongkir'            => 5000,
                'total_harga'       => 25000,
            ],
            [
                'resto'             => 'Sinar Djaya Resto',
                'nama_penerima'     => 'Budi Santoso',
                'alamat_pengiriman' => 'Jl. Dukuh Kupang No. 7',
                'gambar_makanan'    => 'assets/images/sinar-djaya.png',
                'status'            => 'on-going',
                'status_courier'    => 'on-going',
                'metode_pengiriman' => 'Delivery',
                'ongkir'            => 5000,
                'total_harga'       => 35000,
            ],
            [
                'resto'             => 'Warung Pak Harto',
                'nama_penerima'     => 'Siti Rahayu',
                'alamat_pengiriman' => 'Jl. Nginden Semolo No. 15',
                'gambar_makanan'    => 'assets/images/nasi-goreng-card.png',
                'status'            => 'on-going',
                'status_courier'    => 'on-going',
                'metode_pengiriman' => 'Delivery',
                'ongkir'            => 5000,
                'total_harga'       => 20000,
            ],
            [
                'resto'             => 'Royal Kitchen',
                'nama_penerima'     => 'Dewi Lestari',
                'alamat_pengiriman' => 'Jl. Raya ITS Blok C No. 3',
                'gambar_makanan'    => 'assets/images/nasi-goreng.png',
                'status'            => 'on-going',
                'status_courier'    => 'on-going',
                'metode_pengiriman' => 'Delivery',
                'ongkir'            => 5000,
                'total_harga'       => 45000,
            ],
            [
                'resto'             => 'Ayam Bakar Wong Solo',
                'nama_penerima'     => 'Eko Prasetyo',
                'alamat_pengiriman' => 'Jl. Arif Rahman Hakim No. 12',
                'gambar_makanan'    => 'assets/images/nasi-goreng.png',
                'status'            => 'on-going',
                'status_courier'    => 'on-going',
                'metode_pengiriman' => 'Delivery',
                'ongkir'            => 5000,
                'total_harga'       => 30000,
            ],
        ];

        // Pastikan ada sampel makanan di daftar_makanans untuk item_pesanans
        $sampleMakanan = DaftarMakanan::first() ?? DaftarMakanan::create([
            'nama_makanan' => 'Nasi Goreng Spesial',
            'deskripsi'    => 'Nasi goreng lezat dengan bumbu rempah pilihan',
            'harga'        => 20000,
            'stok'         => 10,
            'status'       => true,
            'gambar'       => 'assets/images/nasi-goreng.png',
        ]);

        foreach ($pesananDummy as $pData) {
            $resto = $restoranMap[$pData['resto']];

            // Cek apakah pesanan untuk penerima ini sudah ada
            $pesanan = Pesanan::firstOrCreate(
                [
                    'nama_penerima' => $pData['nama_penerima'],
                ],
                [
                    'restoran_id'       => $resto->id,
                    'alamat_pengiriman' => $pData['alamat_pengiriman'],
                    'gambar_makanan'    => $pData['gambar_makanan'],
                    'status'            => $pData['status'],
                    'status_courier'    => $pData['status_courier'],
                    'metode_pengiriman' => $pData['metode_pengiriman'],
                    'ongkir'            => $pData['ongkir'],
                    'total_harga'       => $pData['total_harga'],
                ]
            );

            // Update field jika sudah ada agar sesuai dengan seeder
            $pesanan->update([
                'restoran_id'       => $resto->id,
                'alamat_pengiriman' => $pData['alamat_pengiriman'],
                'gambar_makanan'    => $pData['gambar_makanan'],
                'status'            => $pData['status'],
                'status_courier'    => $pData['status_courier'],
                'metode_pengiriman' => $pData['metode_pengiriman'],
                'ongkir'            => $pData['ongkir'],
                'total_harga'       => $pData['total_harga'],
            ]);

            // Pastikan relasi item_pesanans terhubung
            ItemPesanan::firstOrCreate(
                [
                    'pesanan_id'        => $pesanan->id,
                    'daftar_makanan_id' => $sampleMakanan->id,
                ],
                [
                    'kuantitas'     => 1,
                    'status_driver' => 'belum_diambil_driver',
                ]
            );
        }
    }
}
