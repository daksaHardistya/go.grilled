<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk_satuan extends Model
{
    use HasFactory;
    protected $table = 'tabel_produk'; // <- ini yang penting
    protected $primaryKey = 'id_produk'; // jika primary key kamu bukan 'id'
    public $timestamps = true;
    protected $fillable = [
        // 'id_produk',
        'kode_produk',
        'nama_produk',
        'harga_produk',
        'stock_produk',
        'image_produk',
    ];
}

