<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absensis;

class Mahasiswi extends Model
{
    use HasFactory;

    protected $table = 'mahasiswi';
    protected $primaryKey = 'id_mahasiswi';
    protected $fillable = [
        'nama_mahasiswi', 'prodi', 'semester',
        'id_muhafidzoh', 'id_dosen', 'id_kelompok', 'id_tempat'
    ];

    // 🔹 Relasi ke tabel DPA (Dosen)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
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

    // Di dalam class Mahasiswi extends Model ...

    // ... (Relasi dosen, muhafidzoh, dll biarkan ada) ...

    // TAMBAHKAN INI:
    public function absensi()
    {
        // Pastikan kamu punya tabel 'absensis' di database baru
        // dan foreign key-nya adalah 'id_mahasiswi'
        return $this->hasMany(Absensis::class, 'id_mahasiswi', 'id_mahasiswi');
    }
}

