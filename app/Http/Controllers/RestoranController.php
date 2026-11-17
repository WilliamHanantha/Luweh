<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Restoran;

class RestoranController extends Controller
{
    public function index()
    {
        // Keluar dari Food List (kembali ke Customer Home): kosongkan keranjang
        Pesanan::where('status', 'Keranjang')->delete();

        $restorans = Restoran::orderByDesc('rating')->get();

        return view('customer.customer-home', [
            'restorans' => $restorans,
        ]);
    }
}
