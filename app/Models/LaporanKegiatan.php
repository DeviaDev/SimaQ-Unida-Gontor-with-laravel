<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    protected $table = 'laporan_kegiatan';
    protected $guarded = [];
    
    // Agar kolom detail_absensi otomatis jadi array saat diambil, dan jadi JSON saat disimpan
    protected $casts = [
        'detail_absensi' => 'array',
    ];
}