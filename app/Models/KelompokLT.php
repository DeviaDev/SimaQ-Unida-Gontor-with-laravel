<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokLT extends Model
{
    use HasFactory;

    protected $table = 'kelompok_lt'; // ✅ sesuai dengan nama tabel di database kamu
    protected $primaryKey = 'id_kelompok';
    protected $fillable = [
        'kode_kelompok',
        'id_muhafidzoh',
        'id_dosen',
        'id_tempat'
    ];


    public function muhafidzoh()
    {
        return $this->hasMany(Muhafidzoh::class, 'id_kelompok');
    }

    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'id_kelompok');
    }

    public function mahasiswi()
    {
        return $this->hasMany(Mahasiswi::class, 'id_kelompok');
    }

     public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }
    

}
