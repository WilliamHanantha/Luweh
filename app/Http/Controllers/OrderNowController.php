<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Restoran;

class OrderNowController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with('itemPesanans.daftarMakanan')
            ->where('status', 'Keranjang')
            ->first();

        $grup = collect();
        $kembaliUrl = url('/customer-home');

        if ($pesanan && $pesanan->itemPesanans->isNotEmpty()) {
            $namaResto = Restoran::pluck('nama_resto', 'id');

            // Kelompokkan item per restoran
            $grup = $pesanan->itemPesanans
                ->groupBy(fn ($item) => $item->daftarMakanan->restoran_id)
                ->map(fn ($items, $restoranId) => [
                    'nama' => $namaResto[$restoranId] ?? 'Restaurant',
                    'items' => $items,
                ]);

            // Tombol kembali: ke Food List restoran dari item yang terakhir ditambahkan
            $terakhir = $pesanan->itemPesanans->sortByDesc('id')->first();
            if ($terakhir->daftarMakanan->restoran_id) {
                $kembaliUrl = url('/food-list/' . $terakhir->daftarMakanan->restoran_id);
            }
        }

        return view('customer.order-now', [
            'pesanan' => $pesanan,
            'grup' => $grup,
            'kembaliUrl' => $kembaliUrl,
        ]);
    }

    public function tambah($id)
    {
        $item = ItemPesanan::findOrFail($id);
        $item->increment('kuantitas');

        return redirect('/order-now');
    }

    public function kurang($id)
    {
        $item = ItemPesanan::with('daftarMakanan')->findOrFail($id);
        $pesanan = $item->pesanan;
        $restoranId = $item->daftarMakanan->restoran_id ?? null;

        if ($item->kuantitas > 1) {
            $item->decrement('kuantitas');
            return redirect('/order-now');
        }

        // Jumlah menjadi 0: hapus item pesanan
        $item->delete();

        // Kalau pesanan tidak punya item lagi, hapus pesanan "Keranjang"
        // dan kembalikan pelanggan ke halaman Food List.
        if ($pesanan->itemPesanans()->count() === 0) {
            $pesanan->delete();
            return redirect($restoranId ? '/food-list/' . $restoranId : '/customer-home');
        }

        return redirect('/order-now');
    }
}
