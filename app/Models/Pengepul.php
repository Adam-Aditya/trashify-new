<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengepul extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengepuls';

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'location',
        'nama_toko',
        'kategori_sampah',
        'is_buka',
        'poin',
    ];

    protected $hidden = [
        'password',
    ];
}