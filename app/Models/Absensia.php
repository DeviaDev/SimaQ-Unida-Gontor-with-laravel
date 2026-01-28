<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensia extends Model
{
    use HasFactory;

    protected $table = 'absensia';
    
    // Pastikan koneksi menggunakan default (db_admin_markaz)
    // Jika db_admin_markaz bukan default di .env, uncomment baris bawah:
    // protected $connection = 'mysql_admin_markaz'; 

    protected $fillable = [
        'id_mahasiswi', 
        'id_muhafidzoh', // ✅ WAJIB DITAMBAHKAN
        'pertemuan',
        'tanggal',
        'status',
        'gedung',
    ];

    public $timestamps = true; // Ubah ke true jika tabel absensia punya created_at/updated_at
}