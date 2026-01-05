<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus2 extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang baru kita buat
    protected $table = 'pengurus';

    protected $fillable = [
        'nim', 'nama', 'prodi', 'sem'
    ];
}