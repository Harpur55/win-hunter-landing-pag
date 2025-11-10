<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryKejuaraan extends Model
{
    use HasFactory;

    protected $table = 'history_kejuaraan';

    protected $fillable = [
        'siswa_id',
        'kejuaraan_id',
        'nama_kejuaraan',
        'lokasi',
        'tanggal',
        'kategori_pertandingan',
        'medali',
        'nama_peserta',
        'kategori_atlit',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kejuaraan()
    {
        return $this->belongsTo(Kejuaraan::class);
    }
}
