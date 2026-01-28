<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujiantahsin extends Model
{
    protected $table = 'ujian_tahsin'; // Sesuaikan nama tabelnya
    protected $primaryKey = 'id_tahsin';
    protected $fillable = [
    'id_mahasiswi', 
    'materi', 
    'nilai', 
    'keterangan_ujian', 
    'prodi', 
    'semester'
];

    public function mahasiswi()
    {
        return $this->belongsTo(Mahasiswi::class, 'id_mahasiswi');
    }

    public function dosen()
{
    // Kita "meminjam" kolom id_mahasiswi untuk menampung ID Dosen juga
    // Parameter ke-3 ('id_dosen') sesuaikan dengan Primary Key di tabel Dosen kamu
    return $this->belongsTo(Dosen::class, 'id_mahasiswi', 'id_dosen'); 
}

// Relasi ke Muhafidzoh
public function muhafidzoh()
{
    // Parameter 1: Model Tujuan
    // Parameter 2: Foreign Key di tabel Ujian (id_mahasiswi)
    // Parameter 3: Primary Key di tabel Muhafidzoh (KITA GANTI JADI 'id_muhafidzoh')
    
    return $this->belongsTo(\App\Models\Muhafidzoh::class, 'id_mahasiswi', 'id_muhafidzoh'); 
}
}