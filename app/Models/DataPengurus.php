<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPengurus extends Model
{
    protected $table = 'pengurus'; 
    protected $fillable = ['nama', 'email', 'foto'];
}
