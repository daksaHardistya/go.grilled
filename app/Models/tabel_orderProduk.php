<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tabel_orderProduk extends Model
{
    use HasFactory;
    protected $table = 'tabel_orderproduk';
    protected $primaryKey = 'id_orderProduk';
    public $timestamps = false;
    protected $fillable = [
        'id_order',
        'id_produk',
        'jumlah_orderProduk',
        
    ];
    public function produk()
    {
        return $this->belongsTo(produk_satuan::class, 'id_produk');
    }

}
