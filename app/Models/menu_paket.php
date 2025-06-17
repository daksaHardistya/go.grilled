<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class menu_paket extends Model
{
    use HasFactory;
    protected $table = 'tabel_paket';
    protected $primaryKey = 'id_paket';
    public $timestamps = true;
    protected $fillable = [
        // 'id_paket',
        'kode_paket',
        'nama_paket',
        'detail_paket',
        'kategori_paket',
        'harga_paket',
        'stock_paket',
        'image_paket',
    ];
}
