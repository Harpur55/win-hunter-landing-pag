<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UjianSiswa extends Pivot
{
    protected $table = 'event_ujian_siswa';
    protected $fillable = [
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'no_register',
        'units_id',
        'kelas_id',
        'event_ujian_id',
        'siswa_id',
        'current_belt_level',
        'next_belt_level',
        'geup',
        'jenis_kelamin',
        'keterangan',
    ];

    public $incrementing = true; // jika tabel pakai id auto increment

    public function eventUjian()
    {
        return $this->belongsTo(EventUjian::class, 'event_ujian_id');
    }

    public function siswa()
{
    return $this->belongsToMany(Siswa::class, 'event_ujian_siswa')
                ->withPivot([
                    'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'no_register',
                    'units_id', 'kelas_id', 'current_belt_level', 
                    'next_belt_level', 'keterangan'
                ]);
}
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
