<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPengurus extends Model
{
    use HasFactory;

    protected $table = 'absensi_pengurus';
    protected $guarded = ['id'];

    // Relasi ke tabel Pengurus (untuk ambil nama pengurus)
    public function pengurus()
    {
        // Pastikan model Pengurus kamu ada di App\Models\Pengurus
        return $this->belongsTo(Pengurus::class, 'id_pengurus');
    }
}