<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorSampah extends Model
{
    protected $fillable = [
        'nama',
        'jenis',
        'berat',
        'harga',
        'deskripsi'
    ];
}

