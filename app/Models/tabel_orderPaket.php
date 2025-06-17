<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tabel_orderPaket extends Model
{
    use HasFactory;
    protected $table = 'tabel_orderpaket';
    protected $primaryKey = 'id_orderPaket';
    public $timestamps = true;
    protected $fillable = [
        // 'id_orderPaket',
        'id_order',
        'id_paket',
        'jumlah_orderPaket',
        
    ];
    public function paket()
    {
        return $this->belongsTo(menu_paket::class, 'id_paket');
    }
}
