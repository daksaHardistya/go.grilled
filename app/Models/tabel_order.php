<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tabel_order extends Model
{
    use HasFactory;
    protected $table = 'tabel_order';
    protected $primaryKey = 'id_order';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'id_pel',
        'nomor_transaksi',
        'tipe_pembayaran',
        'total_belanjaan',
        'status_order',
        'bukti_pembayaran',
    ];
    public function data_pelanggan()
    {
        return $this->belongsTo(data_pelanggan::class, 'id_pel');
    }

    public function order_produk()
    {
        return $this->hasMany(tabel_orderProduk::class, 'id_order');
    }

    public function order_paket()
    {
        return $this->hasMany(tabel_orderPaket::class, 'id_order');
    }
}
