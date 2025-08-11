<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUjian extends Model
{
    protected $table = 'event_ujian';
    protected $fillable = [
        'nama_ujian',
        'tanggal_ujian',
        'lokasi_ujian',
    ];
   
    public function siswa()
{
    return $this->belongsToMany(Siswa::class, 'event_ujian_siswa')
                ->withPivot(['current_belt_level', 'next_belt_level', 'keterangan']); // Pastikan untuk menyertakan semua kolom pivot
}
    public function dataUjians()
{
    return $this->hasMany(DataUjian::class, 'event_ujian_id');
}
    
}
