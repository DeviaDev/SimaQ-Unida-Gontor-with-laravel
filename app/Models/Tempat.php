<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    use HasFactory;

    protected $table = 'tempat';
    protected $primaryKey = 'id_tempat';
    protected $fillable = ['nama_tempat'];

    public function kelompok()
    {
        return $this->hasMany(KelompokLT::class, 'id_tempat');
    }

    public function muhafidzoh()
    {
        return $this->hasMany(Muhafidzoh::class, 'id_tempat');
    }

    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'id_tempat');
    }

    public function mahasiswi()
    {
        return $this->hasMany(Mahasiswi::class, 'id_tempat');
    }
}
