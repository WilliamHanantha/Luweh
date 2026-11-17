<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SertifikatDigital;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MilestoneController extends Controller
{
    public function index()
    {
        // Sementara hanya untuk pengujian lokal.
        // Autentikasi pengguna belum tersedia.
        abort_unless(app()->environment('local'), 403);

        // Ambil akun Demo Customer beserta kontribusinya.
        $customer = User::with('kontribusi')
            ->where('email', 'demo.customer@luweh.test')
            ->first();

        // Ambil poin dari database.
        // Jika kontribusi belum ada, tampilkan 0.
        $points = (int) ($customer?->kontribusi?->total_point ?? 0);

        // Belum tersedia di database.
        $foodSaved = null;

        // Sementara menggunakan poin.
        // Syarat Food Saved akan ditambahkan
        // setelah sumber datanya tersedia.
        $eligible = $points >= 100;

        // Kirim data ke halaman Milestone.
        return view('milestone', compact(
            'points',
            'foodSaved',
            'eligible'
        ));
    }


public function downloadCertificate()
{
    // Hanya untuk pengujian akun demo di lingkungan lokal
    abort_unless(app()->environment('local'), 403);

    // Mengambil data Demo Customer dan kontribusinya
    $customer = User::with('kontribusi')
        ->where('email', 'demo.customer@luweh.test')
        ->firstOrFail();

    $kontribusi = $customer->kontribusi;

    abort_if(
        $kontribusi === null,
        403,
        'Data kontribusi belum tersedia.'
    );

    $totalPoin = (int) $kontribusi->total_point;

    // Belum ada sumber berat makanan di database
    $totalFoodSaved = null;

    // UC07: minimal 25 kg Food Saved ATAU 100 poin
    // Untuk pengujian saat ini, hanya jalur poin yang tersedia
    $eligible = $totalPoin >= 100 ||
        ($totalFoodSaved !== null && $totalFoodSaved >= 25);

    abort_unless(
        $eligible,
        403,
        'Kontribusi belum memenuhi syarat sertifikat.'
    );

    $tanggal = now();

    // Menghasilkan PDF dari template Blade
    $pdf = Pdf::loadView('certificates.luweh', [
        'namaPenerima' => $customer->name,
        'totalFoodSaved' => $totalFoodSaved,
        'totalPoin' => $totalPoin,
        'tanggalTerbit' => $tanggal->format('d-m-Y'),
    ])->setPaper('a4', 'landscape');

    // Nama file unik untuk setiap penerbitan
    $filePath = 'certificates/' . Str::uuid() . '.pdf';

    // Menyimpan PDF pada storage privat Laravel
    $saved = Storage::disk('local')->put(
        $filePath,
        $pdf->output()
    );

    abort_unless($saved, 500, 'Gagal menyimpan sertifikat.');

    try {
        // Menyimpan riwayat penerbitan ke MySQL
        SertifikatDigital::create([
            'kontribusi_id' => $kontribusi->id,
            'total_food_saved' => $totalFoodSaved,
            'file_path' => $filePath,
            'issued_at' => $tanggal,
        ]);
    } catch (\Throwable $e) {
        // Hapus file jika penyimpanan riwayat gagal
        Storage::disk('local')->delete($filePath);
        throw $e;
    }

    // Mengunduh sertifikat
    return Storage::disk('local')->download(
        $filePath,
        'Sertifikat-Kontribusi-LUWEH.pdf'
    );
}

}
