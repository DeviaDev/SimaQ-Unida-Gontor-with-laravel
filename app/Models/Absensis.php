<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensis extends Model
{
    use HasFactory;

    // HAPUS baris protected $connection = 'mysql_siwak'; jika ada!
    
    // Sesuaikan nama tabel di db_admin_markaz
    protected $table = 'absensis'; // Cek di phpMyAdmin, apakah 'absensi' atau 'absensis'? Sesuaikan.
    
    protected $guarded = ['id'];

    // Relasi balik ke Mahasiswi (Penting untuk withCount di Controller)
    public function mahasiswi()
    {
        return $this->belongsTo(Mahasiswi::class, 'id_mahasiswi', 'id_mahasiswi');
    }
}