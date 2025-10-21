<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUjian extends Model
{
    protected $table = 'event_ujian';
    protected $fillable = [
        'nama_ujian',
        'tanggal_ujian',
        'is_regitration_closed',
        'lokasi_ujian',
    ];
   
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'event_ujian_siswa', 'event_ujian_id', 'siswa_id')
                    ->withPivot(['current_belt_level', 'next_belt_level', 'keterangan'])
                    ->withTimestamps();
    }
    public function dataUjians()
{
    return $this->hasMany(DataUjian::class, 'event_ujian_id');
}
    
}
