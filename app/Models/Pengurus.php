<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengurus extends Model
{  use HasFactory;

    protected $table = 'pengurus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'foto', 'nama', 'email'
    ];
}
