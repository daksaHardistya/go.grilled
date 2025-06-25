<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_pelanggan extends Model
{
    protected $table = 'tabel_pelanggan';
    protected $primaryKey = 'id_pel';
    public $timestamps = true;

    protected $fillable = [
        'nomor_tlp',
        'nama_pel',
        'alamat_pel',
        'email_pel',
    ];
}

