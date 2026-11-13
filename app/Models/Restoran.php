<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restoran extends Model
{
    protected $fillable = ['nama_resto', 'alamat', 'jarak', 'rating', 'gambar'];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

     public function produkMitras()
    {
        return $this->hasMany(ProdukMitra::class);
    }
}
