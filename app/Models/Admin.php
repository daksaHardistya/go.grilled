<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = ['username', 'password'];

    protected $hidden = ['password'];

    // Untuk login pakai username, bukan email
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // (Opsional) Jika tidak pakai timestamps (created_at, updated_at)
    public $timestamps = false;
}
