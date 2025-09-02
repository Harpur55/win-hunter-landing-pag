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
        'kelas_id',
        'current_belt_level',
        // 'next_belt_level',
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
    return $this->belongsTo(Unit::class, 'units_id', 'id');
}


  public function ujian()
{
    return $this->belongsToMany(EventUjian::class, 'event_ujian_siswa')
        ->withPivot(['current_belt_level', 'next_belt_level', 'keterangan'])
        ->withTimestamps();
}
    
    // public function dataUjians()
    // {
    //     return $this->hasMany(DataUjian::class, 'siswas_id');
    // }

    public function kelas()
{
    return $this->belongsTo(Kelas::class, 'kelas_id');
}

 public function kejuaraans()
    {
        return $this->belongsToMany(Kejuaraan::class, 'kejuaraan_siswa', 'siswa_id', 'kejuaraan_id')
            ->withPivot([
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'sabuk',
                'kategori_pertandingan',
                'kategori_atlit',
                'berat_badan',
                'tinggi_badan',
                'medali',
            ])
            ->withTimestamps();
    }

    public function model(array $row)
{
    if ($this->testMode) {
        return null;
    }

    return new Siswa([
        'nis'                => $row['nis'] ?? null,
        'no_register'        => $row['nomor_registrasi'] ?? null,
        'nama_lengkap'       => $row['nama_lengkap'] ?? null,
        'jenis_kelamin'      => $row['jenis_kelamin'] ?? null,
        'units_id'           => !empty($row['unit_latihan']) ? Unit::where('name', $row['unit_latihan'])->value('id') : null,
        'kelas_id'           => !empty($row['kelas']) ? Kelas::where('name', $row['kelas'])->value('id') : null,
        'current_belt_level' => $row['sabuk'] ?? null,
        'tempat_lahir'       => $row['tempat_lahir'] ?? null,
        'tanggal_lahir'      => !empty($row['tanggal_lahir']) ? Carbon::parse($row['tanggal_lahir']) : null,
        'golongan_darah'     => $row['golongan_darah'] ?? null,
        'image'              => $row['foto_siswa'] ?? null,
        'alamat_lengkap'     => $row['alamat_lengkap'] ?? null,
        'no_telepon'         => $row['nomor_telepon'] ?? null,
        'nama_ayah'          => $row['nama_ayah'] ?? null,
        'pekerjaan_ayah'     => $row['pekerjaan_ayah'] ?? null,
        'nama_ibu'           => $row['nama_ibu'] ?? null,
        'pekerjaan_ibu'      => $row['pekerjaan_ibu'] ?? null,
        'status'             => $row['status'] ?? 'Aktif',
        'joint_date'         => !empty($row['tanggal_bergabung']) ? Carbon::parse($row['tanggal_bergabung']) : null,
    ]);
}



}
