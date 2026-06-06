<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetorSampah extends Model
{
    protected $fillable = [
        'user_id',
        'pengepul_id',
        'nama',
        'jenis',
        'berat',
        'harga',
        'status',
        'deskripsi'
    ];
    public function pengguna()
    {
        // Menyatakan bahwa kolom user_id di tabel ini merujuk ke id di tabel Pengguna
        return $this->belongsTo(Pengguna::class, 'user_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id');
    }
}

