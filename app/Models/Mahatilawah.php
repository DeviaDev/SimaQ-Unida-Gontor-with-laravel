<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahatilawah extends Model
{
    use HasFactory;

    protected $connection = 'mysql_siwak'; 
    
    // PENTING: Karena nama model 'Mahatilawah' tapi tabelnya 'mahatahsin'
    protected $table = 'mahatahsin'; 

    protected $fillable = [
        'mahasiswi_id',
        'juz',
        'khatam_ke',
    ];
}