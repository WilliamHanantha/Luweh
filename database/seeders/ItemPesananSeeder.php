<?php

namespace Database\Seeders;

use App\Models\DaftarMakanan;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Restoran;
use Illuminate\Database\Seeder;

class ItemPesananSeeder extends Seeder
{
    /**
     * Seed 5 item pesanan dummy dengan status_driver 'belum_diambil_driver'.
     * Item pertama (Order #1) sudah ada dari PesananSeeder; seeder ini
     * membuat pesanan baru khusus untuk halaman Order FnB Owner.
     */
    public function run(): void
    {
        // Data makanan dummy beserta gambarnya
        $menuDummy = [
            [
                'nama_makanan' => 'Nasi Goreng Spesial',
                'deskripsi'    => 'Nasi goreng dengan topping telur dan ayam',
                'harga'        => 15000,
                'stok'         => 10,
                'status'       => true,
                'gambar'       => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_makanan' => 'Mie Goreng Jawa',
                'deskripsi'    => 'Mie goreng rempah khas Jawa',
                'harga'        => 12000,
                'stok'         => 8,
                'status'       => true,
                'gambar'       => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_makanan' => 'Ayam Bakar Madu',
                'deskripsi'    => 'Ayam bakar dengan bumbu madu manis',
                'harga'        => 20000,
                'stok'         => 6,
                'status'       => true,
                'gambar'       => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_makanan' => 'Soto Ayam',
                'deskripsi'    => 'Soto ayam kuah bening segar',
                'harga'        => 13000,
                'stok'         => 12,
                'status'       => true,
                'gambar'       => 'assets/images/nasi-goreng.png',
            ],
            [
                'nama_makanan' => 'Gado-Gado',
                'deskripsi'    => 'Sayuran segar dengan saus kacang',
                'harga'        => 10000,
                'stok'         => 15,
                'status'       => true,
                'gambar'       => 'assets/images/nasi-goreng.png',
            ],
        ];

        // Pastikan restoran ada
        $restoran = Restoran::first();

        // Buat satu pesanan induk untuk semua item dummy ini
        $pesanan = Pesanan::create([
            'restoran_id'       => $restoran?->id,
            'status'            => 'Diproses',
            'metode_pengiriman' => 'Delivery',
            'alamat_pengiriman' => 'Jl. Contoh No. 1, Surabaya',
            'ongkir'            => 5000,
            'total_harga'       => 0, // akan dihitung setelah item dibuat
        ]);

        $totalHarga = 0;

        foreach ($menuDummy as $index => $menu) {
            // firstOrCreate agar tidak duplikat jika seeder dijalankan ulang
            $makanan = DaftarMakanan::firstOrCreate(
                ['nama_makanan' => $menu['nama_makanan']],
                $menu
            );

            // Update gambar jika belum ada
            if (empty($makanan->gambar)) {
                $makanan->update(['gambar' => $menu['gambar']]);
            }

            $kuantitas = $index + 1; // 1, 2, 3, 4, 5 — agar bervariasi

            ItemPesanan::create([
                'pesanan_id'       => $pesanan->id,
                'daftar_makanan_id'=> $makanan->id,
                'kuantitas'        => $kuantitas,
                'status_driver'    => 'belum_diambil_driver',
            ]);

            $totalHarga += $makanan->harga * $kuantitas;
        }

        // Update total harga pesanan
        $pesanan->update(['total_harga' => $totalHarga]);
    }
}
