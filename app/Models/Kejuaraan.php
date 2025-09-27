<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kejuaraan extends Model
{
    //
    protected $table = 'kejuaraans';
    protected $fillable = [
       'nama_kejuaraan','tanggal_mulai','tanggal_selesai','lokasi'
    ];

     public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'kejuaraan_siswa', 'kejuaraan_id', 'siswa_id')
            ->withPivot([
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'sabuk',
                'kategori_pertandingan',
                'tageuk',
                'tingkat_kategori',
                'kategori_atlit',
                'berat_badan',
                'tinggi_badan',
                'medali',
            ])
            ->withTimestamps();
    }
    
}
