<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Unit extends Model
{
    use HasFactory;
    
    protected $table = 'units';

    protected $fillable = [
        'name',
        'description',
        'alamat',
        'image',
        'link',
    ];

 
    public function siswas()
{
    return $this->hasMany(Siswa::class, 'units_id');
}
 
    
}


