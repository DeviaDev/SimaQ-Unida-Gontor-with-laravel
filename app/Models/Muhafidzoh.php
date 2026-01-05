<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muhafidzoh extends Model
{
    use HasFactory;

    // 1. HAPUS baris protected $connection = 'mysql_siwak';
    // Biarkan dia pakai koneksi default (db_admin_markaz)

    // 2. Sesuaikan nama tabel baru
    protected $table = 'muhafidzoh'; 
    protected $primaryKey = 'id_muhafidzoh';

    public $timestamps = false; // Sesuaikan (kalau di db ada created_at, ubah jadi true)

    // 3. Kolom-kolom yang BARU
    protected $fillable = [
        'nama_muhafidzoh',
        'keterangan',
        'id_kelompok',
        'id_tempat'
    ];

    // --- RELASI (PENTING AGAR IMPORT BERJALAN) ---

    // Relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(KelompokLT::class, 'id_kelompok', 'id_kelompok');
    }

    // Relasi ke Tempat
    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }
}