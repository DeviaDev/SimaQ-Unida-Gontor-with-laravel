<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
 use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $fillable = [
        'nama_dosen','id_muhafidzoh', 'id_kelompok', 'id_tempat'
    ];

    public function mahasiswi()
    {
        return $this->hasMany(Mahasiswi::class, 'id_dosen', 'id_dosen');
    }

    // 🔹 Relasi ke tabel Muhafidzoh
    public function muhafidzoh()
    {
        return $this->belongsTo(Muhafidzoh::class, 'id_muhafidzoh', 'id_muhafidzoh');
    }

    // 🔹 Relasi ke tabel Kelompok
    public function kelompok()
    {
    return $this->belongsTo(KelompokLT::class, 'id_kelompok', 'id_kelompok');
    }


    // 🔹 Relasi ke tabel Tempat
    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }

}
