<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UjianSiswa extends Pivot
{
    protected $table = 'event_ujian_siswa';
    protected $fillable = [
        'event_ujian_id',
        'siswa_id',
        'current_belt_level',
        'next_belt_level',
        'geup',
        'keterangan',
    ];

    public $incrementing = true; // jika tabel pakai id auto increment

    public function eventUjian()
    {
        return $this->belongsTo(EventUjian::class, 'event_ujian_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
