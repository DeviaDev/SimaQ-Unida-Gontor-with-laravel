<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muhafidzoh extends Model
{
    use HasFactory;

    protected $table = 'muhafidzoh';
    protected $primaryKey = 'id_muhafidzoh';
    protected $fillable = ['nama_muhafidzoh', 'keterangan', 'id_kelompok', 'id_tempat'];

public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_muhafidzoh', 'id_muhafidzoh');
    }

public function kelompok()
    {
        return $this->belongsTo(KelompokLT::class, 'id_kelompok', 'id_kelompok');
    }

    // 🔹 Relasi ke tabel Tempat
    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }

    public function mahasiswi()
    {
        return $this->hasMany(Mahasiswi::class, 'id_muhafidzoh', 'id_muhafidzoh');
    }

}
