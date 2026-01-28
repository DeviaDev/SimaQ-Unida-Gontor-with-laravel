<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahatilawah extends Model
{
    use HasFactory;

    // 👇 UPDATE: Sesuai instruksi
    // protected $connection = 'db_admin_markaz'; 
    
    // 👇 UPDATE: Sesuai nama tabel baru
    protected $table = 'mahatilawah'; 

    protected $fillable = [
        'mahasiswi_id',
        'juz',
        'khatam_ke',
    ];
}