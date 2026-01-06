<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TilawahPengurus extends Model
{
    use HasFactory;

    protected $table = 'tilawah_pengurus';
    
    protected $fillable = [
        'id_pengurus',
        'juz',
        'khatam_ke',
    ];

    // Relasi balik ke Pengurus (Opsional, jika dibutuhkan)
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'id_pengurus');
    }
}