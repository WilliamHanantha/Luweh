<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    /**
     * Halaman Food Hero: hanya menampilkan pesanan yang statusnya 'on-going'.
     */
    public function foodHero()
    {
        $pesanans = Pesanan::with(['restoran', 'itemPesanans.daftarMakanan'])
            ->whereNotNull('nama_penerima')
            ->where(function ($query) {
                $query->where('status_courier', 'on-going')
                      ->orWhere('status', 'on-going');
            })
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
            'status'         => 'on-delivery',
            'status_courier' => 'on-delivery',
        ]);

        // Sinkronkan status item pesanan dengan driver
        $pesanan->itemPesanans()->update([
            'status_driver' => 'sudah_diambil_driver',
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
     * Halaman Activity: menampilkan pesanan kurir yang sedang diantar ('on-delivery')
     * dan yang sudah selesai ('finished').
     */
    public function activity()
    {
        $pesanans = Pesanan::with(['restoran', 'itemPesanans.daftarMakanan'])
            ->whereNotNull('nama_penerima')
            ->where(function ($query) {
                $query->whereIn('status_courier', ['on-delivery', 'finished'])
                      ->orWhereIn('status', ['on-delivery', 'finished']);
            })
            ->orderByRaw("CASE WHEN status_courier = 'on-delivery' OR status = 'on-delivery' THEN 1 ELSE 2 END")
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('courier.activity', compact('pesanans'));
    }

    /**
     * Courier menyelesaikan pesanan: status berubah menjadi 'finished'.
     */
    public function deliverOrder(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'status'         => 'finished',
            'status_courier' => 'finished',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan #' . $pesanan->id . ' telah sampai dan selesai diantar!',
                'status'  => 'finished',
            ]);
        }

        return redirect()->route('courier.activity')->with('success', 'Pesanan #' . $pesanan->id . ' telah sampai dan selesai diantar!');
    }
}
