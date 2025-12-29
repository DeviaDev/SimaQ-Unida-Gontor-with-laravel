<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muhafidzoh extends Model
{
    use HasFactory;
    protected $connection = 'mysql_siwak';
    protected $table = 'muhafidzoh2';
    public $timestamps = false;

    // kolom yang boleh diisi
    protected $fillable = [
        'nama',
        'ket',
        'kelompok',
        'gedung',
        'ruang'
    ];

}
