<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kontribusi;
use App\Models\ProdukMitra;
use App\Models\PenukaranPoin;
use Illuminate\Support\Facades\DB;

class PenukaranPoinController extends Controller
{
    /**
     * Demo courier identification.
     * Temporary because LUWEH has no authentication system.
     */
    private function demoCourier(): User
    {
        $courier = User::where(
            'email',
            'demo.courier@luweh.test'
        )->firstOrFail();

        abort_unless(
            $courier->role === 'courier',
            403,
            'Only couriers can redeem points.'
        );

        return $courier;
    }

    /**
     * Display Redeem Points page.
     */
    public function index()
    {
        $courier = $this->demoCourier();

        $kontribusi = $courier->kontribusi()
            ->firstOrCreate([], [
                'total_point' => 0,
            ]);

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
    public function confirm($id)
    {
        $courier = $this->demoCourier();

        $kontribusi = $courier->kontribusi()
            ->firstOrCreate([], [
                'total_point' => 0,
            ]);

        $produkMitra = ProdukMitra::with('restoran')
            ->findOrFail($id);

        $canRedeem =
            $produkMitra->status &&
            $produkMitra->stok > 0 &&
            $kontribusi->total_point >=
                $produkMitra->poin_dibutuhkan;

        return view('courier.redeem-confirm', compact(
            'courier',
            'kontribusi',
            'produkMitra',
            'canRedeem'
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

            // Check product availability
            abort_unless(
                $produkMitra->status && $produkMitra->stok > 0,
                409,
                'Product is currently unavailable.'
            );

            // Check sufficient points
            abort_if(
                $kontribusi->total_point <
                $produkMitra->poin_dibutuhkan,
                422,
                'Your contribution points are insufficient.'
            );

            $pointsUsed = $produkMitra->poin_dibutuhkan;

            // Deduct contribution points
            $kontribusi->total_point -= $pointsUsed;
            $kontribusi->save();

            // Reduce product stock
            $produkMitra->stok -= 1;
            $produkMitra->save();

            // Record redemption transaction
            return PenukaranPoin::create([
                'user_id' => $courier->id,
                'produk_mitra_id' => $produkMitra->id,
                'jumlah_poin_digunakan' => $pointsUsed,
            ]);
        });

        return redirect()
            ->route('courier.redeem.receipt', $penukaran->id)
            ->with('success', 'Points successfully redeemed!');
    }

    /**
     * Display redemption receipt.
     */
    public function receipt($id)
    {
        $courier = $this->demoCourier();

        $penukaran = PenukaranPoin::with([
            'produkMitra.restoran'
        ])
            ->where('user_id', $courier->id)
            ->findOrFail($id);

        return view('courier.redeem-receipt', compact(
            'courier',
            'penukaran'
        ));
    }
}
