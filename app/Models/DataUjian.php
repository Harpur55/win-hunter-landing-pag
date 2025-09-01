<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Unit;
use App\Models\EventUjian;
use App\Models\Siswa;



class DataUjian extends Model
{
       

    protected $table = 'data_ujian';

    protected $fillable = [
        'siswas_id',
        'units_id',
        'kelas_id',
        'event_ujian_id',
        'tempat_lahir',
        'tanggal_lahir',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswas_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function eventUjian()
    {
        return $this->belongsTo(EventUjian::class, 'event_ujian_id');
    }
}
