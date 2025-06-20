<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    
    protected $table = 'units';

    protected $fillable = [
        'name',
        'description',
    ];

    // Define any relationships or additional methods if needed
}
