<?php

namespace App\Http\Controllers;

use App\Models\Restoran;

class RestoranController extends Controller
{
    public function index()
    {
        $restorans = Restoran::orderByDesc('rating')->get();

        return view('customer.customer-home', [
            'restorans' => $restorans,
        ]);
    }
}
