<?php

namespace App\Http\Controllers;

use App\Models\DaftarMakanan;
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
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        $makanan = DaftarMakanan::create($validatedData);

        return response()->json($makanan, 201);
    }

    public function create()
    {
        return view('fnb-owner.createMakanan');
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
