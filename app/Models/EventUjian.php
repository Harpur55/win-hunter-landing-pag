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
        ->using(\App\Models\UjianSiswa::class) // ✅ penting supaya pivot pakai model kustom
        ->withPivot(['current_belt_level', 'next_belt_level', 'geup', 'keterangan','jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'no_register', 'nama_lengkap'])
        ->withTimestamps();
}

   public function ujianSiswa()
{
    return $this->hasMany(UjianSiswa::class, 'event_ujian_id');
}
    
}
