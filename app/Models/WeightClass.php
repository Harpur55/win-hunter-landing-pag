<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightClass extends Model
{
    protected $table = 'weight_class';
    protected $fillable = [
        'kategori_atlit',
        'jenis_kelamin',
        'min_kg',
        'max_kg',
        'label',
    ];
}
