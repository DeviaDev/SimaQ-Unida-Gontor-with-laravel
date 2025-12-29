<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswi2 extends Model
{
    protected $connection = 'mysql_siwak';
    protected $table = 'mahasiswi2'; // ← NAMA TABEL DI PHPMYADMIN
    public $timestamps = false;      // kalau tabel kamu tidak ada created_at

    protected $fillable = [
        'nama',
        'prodi',
        'semester',
        'muhafidzoh',
        'kelompok',
        'tempat',
        'dpa'
    ];
}
