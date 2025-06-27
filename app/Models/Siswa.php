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
        'unit_latihan',
        'kelas',
        'sabuk',
        'joint_date',
        'status',
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
    return $this->belongsTo(Unit::class);
}
}
