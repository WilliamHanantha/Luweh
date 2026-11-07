<?php

namespace Database\Seeders;

use App\Models\DaftarMakanan;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Restoran;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $makanans = [
            ['nama_makanan' => 'Nasi Goreng', 'deskripsi' => 'Nasi goreng sisa produksi hari ini', 'harga' => 10000, 'stok' => 5, 'status' => true],
            ['nama_makanan' => 'Mie Ayam', 'deskripsi' => 'Mie ayam sisa produksi hari ini', 'harga' => 8000, 'stok' => 5, 'status' => true],
        ];

        $ids = [];
        foreach ($makanans as $m) {
            $ids[] = DaftarMakanan::firstOrCreate(['nama_makanan' => $m['nama_makanan']], $m)->id;
        }

        $pesanan = Pesanan::firstOrCreate(
            ['status' => 'Keranjang'],
            ['restoran_id' => Restoran::first()?->id]
        );

        foreach ($ids as $id) {
            ItemPesanan::firstOrCreate(
                ['pesanan_id' => $pesanan->id, 'daftar_makanan_id' => $id],
                ['kuantitas' => 1]
            );
        }
    }
}
