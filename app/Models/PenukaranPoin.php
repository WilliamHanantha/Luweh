<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenukaranPoin extends Model
{
    use HasFactory;

    protected $table = 'penukaran_points';

    protected $fillable = [
        'user_id',
        'produk_mitra_id',
        'jumlah_poin_digunakan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_poin_digunakan' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produkMitra()
    {
        return $this->belongsTo(ProdukMitra::class);
    }
}
