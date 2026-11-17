<?php

namespace App\Http\Controllers;

use App\Models\DaftarMakanan;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Restoran;

class FoodListController extends Controller
{
    // UC01: tampilkan makanan yang dijual restoran yang dipilih
    public function index(Restoran $restoran)
    {
        $makanans = $restoran->daftarMakanans()
            ->where('status', true)
            ->get();

        // id makanan yang sudah ada di keranjang, untuk menampilkan tanda centang
        $dalamKeranjang = ItemPesanan::whereHas('pesanan', fn ($q) => $q->where('status', 'Keranjang'))
            ->pluck('daftar_makanan_id')
            ->all();

        return view('customer.food-list', [
            'restoran' => $restoran,
            'makanans' => $makanans,
            'dalamKeranjang' => $dalamKeranjang,
        ]);
    }

    // UC02 basic flow dan alternate flow a:
    // pakai pesanan "Keranjang" yang ada, atau buat baru kalau belum ada.
    public function tambah(Restoran $restoran, DaftarMakanan $makanan)
    {
        abort_if($makanan->restoran_id != $restoran->id, 404);

        $keranjang = Pesanan::firstOrCreate(['status' => 'Keranjang']);

        ItemPesanan::firstOrCreate(
            ['pesanan_id' => $keranjang->id, 'daftar_makanan_id' => $makanan->id],
            ['kuantitas' => 1]
        );

        return back();
    }

    // Klik centang lagi: hapus makanan dari pesanan "Keranjang"
    public function hapus(Restoran $restoran, DaftarMakanan $makanan)
    {
        $keranjang = Pesanan::where('status', 'Keranjang')->first();

        if ($keranjang) {
            $keranjang->itemPesanans()
                ->where('daftar_makanan_id', $makanan->id)
                ->delete();

            if ($keranjang->itemPesanans()->count() === 0) {
                $keranjang->delete();
            }
        }

        return back();
    }

    // UC02: tombol Beli (alternate flow b kalau keranjang kosong)
    public function beli()
    {
        $adaKeranjang = Pesanan::where('status', 'Keranjang')
            ->whereHas('itemPesanans')
            ->exists();

        if ($adaKeranjang) {
            return redirect('/order-now');
        }

        return back()->with('pesan', 'Tambahkan makanan terlebih dahulu.');
    }
}
