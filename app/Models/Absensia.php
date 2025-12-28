<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensia extends Model
{
    use HasFactory;

    // Pastikan koneksi sesuai (mysql_siwak)
    protected $connection = 'mysql_siwak'; 
    protected $table = 'absensis_muhafidzoh';
    
    protected $fillable = [
        'muhafidzoh_id',
        'pertemuan',
        'tanggal',
        'status'
    ];
}