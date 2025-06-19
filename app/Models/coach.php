<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class coach extends Model
{
     use HasFactory;

    protected $table = 'coach';
    protected $fillable = [
        'foto',
        'nama',
        'Sabuk',
        'role',
    ];
    //
}
