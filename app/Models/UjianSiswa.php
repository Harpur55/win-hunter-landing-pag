<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSiswa extends Model
{
    //
    protected $table = 'event_ujian_siswa';
    protected $fillable = [
        'event_ujian_id',
        'siswa_id',
        'current_belt_level',
        'next_belt_level',
        'keterangan',
    ];

    public function eventUjian()
    {
        return $this->belongsTo(EventUjian::class, 'event_ujian_id');
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
