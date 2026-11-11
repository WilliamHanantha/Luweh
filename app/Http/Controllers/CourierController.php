<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * Halaman Food Hero: hanya menampilkan pesanan yang statusnya 'picked up' (siap diantar).
     */
    public function foodHero()
    {
        $pesanans = Pesanan::with(['restoran', 'itemPesanans.daftarMakanan'])
            ->whereNotNull('nama_penerima')
            ->where('status', 'picked up')
            ->orderBy('id', 'asc')
            ->get();

        return view('courier.foodhero', compact('pesanans'));
    }

    /**
     * Courier mengambil pesanan: status berubah menjadi 'on-delivery'
     * dan pesanan dipindah ke halaman Activity.
     */
    public function pickOrder(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status' => 'on-delivery',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Pesanan #' . $pesanan->id . ' berhasil diambil untuk diantar!',
                'redirect_url' => route('courier.activity'),
            ]);
        }

        return redirect()->route('courier.activity')->with('success', 'Pesanan #' . $pesanan->id . ' berhasil diambil untuk diantar!');
    }

    /**
     * Halaman Activity: menampilkan pesanan kurir yang sedang aktif diantar ('on-delivery').
     */
    public function activity()
    {
        $pesanans = Pesanan::with(['restoran', 'itemPesanans.daftarMakanan'])
            ->whereNotNull('nama_penerima')
            ->where('status', 'on-delivery')
            ->orderByDesc('updated_at')
            ->get();

        return view('courier.activity', compact('pesanans'));
    }

    /**
     * Courier menyelesaikan pengiriman: status berubah menjadi 'completed'
     * dan pesanan masuk ke riwayat (History).
     */
    public function deliverOrder(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status' => 'completed',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan #' . $pesanan->id . ' telah sampai dan selesai diantar!',
                'status'  => 'completed',
            ]);
        }

        return redirect()->route('courier.activity')->with('success', 'Pesanan #' . $pesanan->id . ' telah sampai dan selesai diantar!');
    }

    /**
     * Halaman History kurir: menampilkan pesanan yang sudah selesai diantar ('completed').
     */
    public function history()
    {
        $pesanans = Pesanan::with(['restoran', 'itemPesanans.daftarMakanan'])
            ->whereNotNull('nama_penerima')
            ->whereIn('status', ['completed', 'complete', 'finished'])
            ->orderByDesc('updated_at')
            ->get();

        return view('courier.history', compact('pesanans'));
    }
}
