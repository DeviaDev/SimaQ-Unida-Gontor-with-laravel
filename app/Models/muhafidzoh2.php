<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muhafidzoh extends Model
{
    use HasFactory;

    protected $table = 'muhafidzoh';
    protected $primaryKey = 'id_muhafidzoh';

    protected $fillable = [
        'nama_muhafidzoh',
        'keterangan',
        'id_kelompok',
        'id_tempat'
    ];

    public $timestamps = false; // 🔥 kalau tabel tidak pakai created_at

    // ======================
    // RELASI
    // ======================

    // 1️⃣ Kelompok
    public function kelompok()
    {
        return $this->belongsTo(
            KelompokLT::class,
            'id_kelompok',
            'id_kelompok'
        );
    }

    // 2️⃣ Tempat (Gedung & Ruang)
    public function tempat()
    {
        return $this->belongsTo(
            Tempat::class,
            'id_tempat',
            'id_tempat'
        );
    }

    // 3️⃣ Dosen (jika ada)
    public function dosen()
    {
        return $this->hasOne(
            Dosen::class,
            'id_muhafidzoh',
            'id_muhafidzoh'
        );
    }

    // 4️⃣ Mahasiswi
    public function mahasiswi()
    {
        return $this->hasMany(
            Mahasiswi::class,
            'id_muhafidzoh',
            'id_muhafidzoh'
        );
    }

    // ======================
    // ACCESSOR (BIAR ENAK DIPANGGIL)
    // ======================

    // 🔹 ambil nama kelompok langsung
    public function getNamaKelompokAttribute()
    {
        return $this->kelompok->nama_kelompok ?? '-';
    }

    // 🔹 ambil gedung langsung
    public function getGedungAttribute()
    {
        return $this->tempat->gedung ?? '-';
    }

    // 🔹 ambil ruang langsung
    public function getRuangAttribute()
    {
        return $this->tempat->ruang ?? '-';
    }
}
