<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';

    protected $fillable = [
        'nama',      // ganti dengan nama kolom yang sesuai di tabel Anda
        'nim',
        'email',     // ganti dengan nama kolom yang sesuai di tabel Anda
        
        // Tambahkan kolom lainnya sesuai kebutuhan
    ];
}
