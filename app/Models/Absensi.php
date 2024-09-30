<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;
    
    protected $table = 'absensi';

    protected $fillable = [
        'anggota_id',
        'status',
        // Hapus 'nama', 'nim', dan 'email' dari sini jika mereka berasal dari model Anggota
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }
}