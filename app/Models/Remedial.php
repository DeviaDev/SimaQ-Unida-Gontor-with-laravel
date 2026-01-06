<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remedial extends Model
{
    use HasFactory;

    // 1. Nama tabel manual supaya tidak error 'remedials' not found
    protected $table = 'remedial'; 

    // 2. Pakai guarded saja (kosong), ini lebih sakti daripada fillable 
    // karena mengizinkan SEMUA kolom masuk tanpa perlu didaftarkan satu-satu
    protected $guarded = []; 

    // 3. Fungsi relasi (Ini WAJIB ada supaya nama mahasiswi muncul di tabel)
    public function mahasiswi()
    {
        return $this->belongsTo(Mahasiswi::class, 'id_mahasiswi', 'id_mahasiswi');
    }
}