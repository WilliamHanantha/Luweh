<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Halaman Order: tampilkan pesanan yang statusnya 'new',
     * lengkap dengan semua item makanan (dari item_pesanans -> daftar_makanans).
     */
    public function order()
    {
        $pesanans = Pesanan::with('itemPesanans.daftarMakanan')
            ->where('status', 'new')
            ->orderBy('id', 'asc')
            ->get();

        return view('fnb-owner.order', compact('pesanans'));
    }

    /**
     * Halaman History: tampilkan pesanan yang statusnya 'picked up', 'on-delivery', atau 'completed'.
     */
    public function history()
    {
        $pesanans = Pesanan::with('itemPesanans.daftarMakanan')
            ->whereIn('status', ['picked up', 'on-delivery', 'complete', 'completed'])
            ->orderByDesc('updated_at')
            ->get();

        return view('fnb-owner.history', compact('pesanans'));
    }

    /**
     * AJAX / POST: update status pesanan menjadi 'picked up'.
     */
    public function updateStatusDriver(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'picked up']);

        return response()->json([
            'success'    => true,
            'message'    => 'Status pesanan berhasil diperbarui.',
            'pesanan_id' => $pesanan->id,
        ]);
    }
}
