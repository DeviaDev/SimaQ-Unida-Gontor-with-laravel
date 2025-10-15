<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KelompokLT extends Model
{
    use HasFactory;

    protected $table = 'kelompok_lt';
    protected $primaryKey = 'id_kelompok';
    protected $fillable = [
        'kode_kelompok',
        'id_muhafidzoh',
        'id_dosen',
        'id_tempat'
    ];

    // ✅ 1 kelompok hanya dimiliki oleh satu dosen dan satu muhafidzoh
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function muhafidzoh()
    {
        return $this->belongsTo(Muhafidzoh::class, 'id_muhafidzoh', 'id_muhafidzoh');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'id_tempat', 'id_tempat');
    }

    // ✅ tapi kelompok punya banyak mahasiswi
    public function mahasiswi()
    {
        return $this->hasMany(Mahasiswi::class, 'id_kelompok', 'id_kelompok');
    }
}

