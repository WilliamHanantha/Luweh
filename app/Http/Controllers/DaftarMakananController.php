<?php

namespace App\Http\Controllers;

use App\Models\DaftarMakanan;
use App\Models\ItemPesanan;
use Illuminate\Http\Request;

class DaftarMakananController extends Controller
{
    public function index()
    {
        return view('fnb-owner.yourMenu', [
            'daftarMakanan' => DaftarMakanan::all()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_makanan' => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'harga'        => 'required|numeric',
            'stok'         => 'nullable|integer',
            'status'       => 'nullable|boolean',
        ]);

        $validatedData['stok'] = $validatedData['stok'] ?? 0;
        $validatedData['status'] = $validatedData['status'] ?? false;

        $makanan = DaftarMakanan::create($validatedData);

        return response()->json($makanan, 201);
    }

    public function setQuantity($id)
    {
        $makanan = DaftarMakanan::findOrFail($id);
        return view('fnb-owner.setQuantity', compact('makanan'));
    }

    public function confirmQuantity(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:1',
        ]);

        $makanan = DaftarMakanan::findOrFail($id);
        $makanan->stok = (int) $request->stok;
        $makanan->status = true; // Aktifkan menu saat berhasil di-post
        $makanan->save();

        return redirect('/your-menu')->with('success', 'Menu "' . $makanan->nama_makanan . '" berhasil di-post dengan stok ' . $makanan->stok . '!');
    }

    public function create()
    {
        return view('fnb-owner.createMakanan');
    }

    /**
     * Halaman Order: tampilkan item pesanan yang BELUM diambil driver,
     * lengkap dengan data makanan (nama, harga, gambar) dari daftar_makanans.
     */
    public function order()
    {
        $items = ItemPesanan::with('daftarMakanan')
            ->where('status_driver', 'belum_diambil_driver')
            ->get();

        return view('fnb-owner.order', compact('items'));
    }

    /**
     * Halaman History: tampilkan item pesanan yang SUDAH diambil driver.
     */
    public function history()
    {
        $items = ItemPesanan::with('daftarMakanan')
            ->where('status_driver', 'sudah_diambil_driver')
            ->get();

        return view('fnb-owner.history', compact('items'));
    }

    /**
     * AJAX / POST: update status_driver item pesanan menjadi 'sudah_diambil_driver'.
     */
    public function updateStatusDriver(Request $request, $id)
    {
        $item = ItemPesanan::findOrFail($id);
        $item->update(['status_driver' => 'sudah_diambil_driver']);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'item_id' => $item->id,
        ]);
    }

    public function toggleStatus($id)
    {
        $makanan = DaftarMakanan::findOrFail($id);
        if ($makanan->status) {
            $makanan->status = false;
            $makanan->stok = 0;
            $makanan->save();

            return redirect()->back()->with('success', 'Menu "' . $makanan->nama_makanan . '" berhasil dinonaktifkan!');
        }

        return redirect('/fnb-owner/' . $id . '/set-quantity');
    }

    public function destroy($id)
    {
        $makanan = DaftarMakanan::findOrFail($id);
        $makanan->delete();

        return response()->json([
            'message' => 'Makanan berhasil dihapus'
        ]);
    }
}
