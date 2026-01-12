<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class coach extends Model
{
     use HasFactory;

    protected $table = 'coach';
    protected $fillable = [
        'foto',
        'nama',
        'sabuk',
        'role',
        'alamat',
        'nomor_telepon',
       
    ];
protected static function booted()
{
    static::updating(function ($coach) {
        if ($coach->isDirty('foto')) {
            $old = $coach->getOriginal('foto');

            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
        }
    });

    static::deleting(function ($coach) {
        if ($coach->foto && Storage::disk('public')->exists($coach->foto)) {
            Storage::disk('public')->delete($coach->foto);
        }
    });
}

public function documents()
{
    return $this->hasMany(CoachDocument::class);
}

    
    //
}
