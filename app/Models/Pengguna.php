<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Menggunakan base auth agar bisa dipakai login

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'penggunas';

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'poin',
    ];

    protected $hidden = [
        'password',
    ];
}