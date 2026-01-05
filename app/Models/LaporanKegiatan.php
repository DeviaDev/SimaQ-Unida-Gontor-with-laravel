<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan';
    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Relasi ke Absensi Pengurus
    public function absensi()
    {
        return $this->hasMany(AbsensiPengurus::class, 'id_kegiatan');
    }
}