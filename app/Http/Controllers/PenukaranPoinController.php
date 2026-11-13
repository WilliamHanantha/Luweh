<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kontribusi;
use App\Models\ProdukMitra;
use App\Models\PenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenukaranPoinController extends Controller
{
    /**
     * Fixed demo courier.
     * No login or account verification required.
     */
    private function demoCourier(): User
    {
        return User::firstOrCreate(
            ['email' => 'demo.courier@luweh.test'],
            [
                'name' => 'Demo Courier',
                'password' => Hash::make('password'),
            ]
        );
    }

    /**
     * Display Redeem Points page.
     */
    public function index()
    {
        $courier = $this->demoCourier();

        $kontribusi = Kontribusi::firstOrCreate(
            ['user_id' => $courier->id],
            ['total_point' => 150]
        );

        $produkMitras = ProdukMitra::with('restoran')
            ->where('status', true)
            ->orderBy('poin_dibutuhkan')
            ->get();

        return view('courier.redeempoints', compact(
            'courier',
            'kontribusi',
            'produkMitras'
        ));
    }

    /**
     * Display redemption confirmation page.
     */
    public function confirm(Request $request, $id)
    {
        $courier = $this->demoCourier();

        $kontribusi = Kontribusi::firstOrCreate(
            ['user_id' => $courier->id],
            ['total_point' => 150]
        );

        $produkMitra = ProdukMitra::with('restoran')
            ->findOrFail($id);

        $canRedeem =
            $produkMitra->status &&
            $produkMitra->stok > 0 &&
            $kontribusi->total_point >=
                $produkMitra->poin_dibutuhkan;

        // Retrieve receipt after successful redemption, if present.
        $penukaran = null;

        if ($request->filled('receipt')) {
            $penukaran = PenukaranPoin::with([
                'produkMitra.restoran'
            ])
                ->where('user_id', $courier->id)
                ->find($request->query('receipt'));
        }

        return view('courier.redeem-confirm', compact(
            'courier',
            'kontribusi',
            'produkMitra',
            'canRedeem',
            'penukaran'
        ));
    }

    /**
     * Process point redemption.
     */
    public function redeem($id)
    {
        $courier = $this->demoCourier();

        $penukaran = DB::transaction(function () use (
            $courier,
            $id
        ) {
            $kontribusi = Kontribusi::where(
                'user_id',
                $courier->id
            )->lockForUpdate()->firstOrFail();

            $produkMitra = ProdukMitra::lockForUpdate()
                ->findOrFail($id);

            abort_unless(
                $produkMitra->status && $produkMitra->stok > 0,
                409,
                'Product is currently unavailable.'
            );

            abort_if(
                $kontribusi->total_point <
                $produkMitra->poin_dibutuhkan,
                422,
                'Your contribution points are insufficient.'
            );

            $pointsUsed = $produkMitra->poin_dibutuhkan;

            // Deduct contribution points.
            $kontribusi->total_point -= $pointsUsed;
            $kontribusi->save();

            // Reduce product stock.
            $produkMitra->stok -= 1;
            $produkMitra->save();

            // Create redemption transaction.
            return PenukaranPoin::create([
                'user_id' => $courier->id,
                'produk_mitra_id' => $produkMitra->id,
                'jumlah_poin_digunakan' => $pointsUsed,
            ]);
        });

        // Return to the confirmation page with its receipt.
        return redirect()->to(
            url('/courier/redeem-points/' .
                $id . '/confirm?receipt=' . $penukaran->id)
        )->with('success', 'Points successfully redeemed!');
    }

    /**
     * Legacy receipt route.
     * The main UC14 flow now displays the receipt
     * directly on the confirmation page.
     */
    
}
