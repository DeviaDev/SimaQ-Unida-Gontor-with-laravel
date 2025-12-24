<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensis extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di database kamu
    protected $table = 'absensis'; 

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'mahasiswi_id',
        'tanggal',
        'status',
        'pertemuan',
    ];
    
    // Atau bisa pakai guarded (kebalikan fillable)
    // protected $guarded = ['id'];
}