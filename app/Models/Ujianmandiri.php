<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ujianmandiri extends Model
{
    protected $primaryKey = 'id_ujian_mandiri';
    protected $table = 'ujian_mandiri';

    protected $fillable = [
        'id_mahasiswi',
        'materi',
        'keterangan_ujian',
        'nilai',
    ];

    public function mahasiswi()
    {
        return $this->belongsTo(Mahasiswi::class, 'id_mahasiswi');
    }
}
