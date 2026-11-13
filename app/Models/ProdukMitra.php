<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukMitra extends Model
{
    use HasFactory;

    protected $table = 'produk_mitras';

    protected $fillable = [
        'restoran_id',
        'nama_produk',
        'deskripsi',
        'gambar',
        'poin_dibutuhkan',
        'stok',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'poin_dibutuhkan' => 'integer',
            'stok' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function restoran()
    {
        return $this->belongsTo(Restoran::class);
    }

    public function penukaranPoints()
    {
        return $this->hasMany(PenukaranPoin::class);
    }
}
