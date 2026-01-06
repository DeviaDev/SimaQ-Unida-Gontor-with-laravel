<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujiantahsin extends Model
{
    protected $table = 'ujian_tahsin'; // Sesuaikan nama tabelnya
    protected $primaryKey = 'id_tahsin';
    protected $fillable = [
    'id_mahasiswi', 
    'materi', 
    'nilai', 
    'keterangan_ujian', 
    'prodi', 
    'semester'
];

    public function mahasiswi()
    {
        return $this->belongsTo(Mahasiswi::class, 'id_mahasiswi');
    }
}