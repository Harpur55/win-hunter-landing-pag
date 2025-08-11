<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    
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
 
    // Define any relationships or additional methods if needed
}


