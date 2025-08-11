<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    //
    protected $fillable = [
       'no_register',
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'image',
        'alamat_lengkap',
        'no_telepon',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'kelas',
        'current_belt_level',
        'next_belt_level',
        'joint_date',
        'status',
        'units_id'
    ];
     protected $casts = [
        'tanggal_lahir' => 'date',
        'joint_date' => 'date',
    ];
    protected $table = 'siswas';

     protected static function boot()
    {
        parent::boot();

        // Ketika sebuah instance model Siswa baru akan disimpan ke database
        static::creating(function ($siswa) {
          
            if (empty($siswa->nis)) {
               
                $lastSiswa = static::orderByDesc('id')->first();
                $lastNis = $lastSiswa ? $lastSiswa->nis : null;        
                $lastNumber = $lastNis ? (int) str_replace('WH-', '', $lastNis) : 0;
                $siswa->nis = 'WH-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
            

        });
        
    }
     public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
    public function unit()
{
    return $this->belongsTo(Unit::class, 'units_id');
}


    public function eventUjians()
    {
        return $this->belongsToMany(EventUjian::class, 'event_ujian_siswa', 'siswa_id', 'event_ujian_id')
                    ->withPivot('keterangan'); // Tambahkan kolom pivot jika perlu
    }
    
    // public function dataUjians()
    // {
    //     return $this->hasMany(DataUjian::class, 'siswas_id');
    // }
}
