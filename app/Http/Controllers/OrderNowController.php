<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Pesanan;

class OrderNowController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with(['itemPesanans.daftarMakanan', 'restoran'])
            ->where('status', 'Keranjang')
            ->first();

        return view('customer.order-now', [
            'pesanan' => $pesanan,
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
        $item = ItemPesanan::findOrFail($id);
        $pesanan = $item->pesanan;

        if ($item->kuantitas > 1) {
            $item->decrement('kuantitas');
            return redirect('/order-now');
        }

        $item->delete();

        if ($pesanan->itemPesanans()->count() === 0) {
            $pesanan->delete();
            return redirect('/customer-home');
        }

        return redirect('/order-now');
    }
}
