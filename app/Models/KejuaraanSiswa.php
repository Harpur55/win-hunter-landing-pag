<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KejuaraanSiswa extends Model
{
    protected $table = 'kejuaraan_siswa';

    protected $fillable = [
        'kejuaraan_id',
        'siswa_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'sabuk',
        'kategori_pertandingan',
        'tageuk',
        'kategori_atlit',
        'berat_badan',
        'tinggi_badan',
        'medali',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function kejuaraan()
    {
        return $this->belongsTo(Kejuaraan::class, 'kejuaraan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers / Accessors
    |--------------------------------------------------------------------------
    */

    // Cek kategori
    public function isKyorugi(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'kyorugi';
    }

    public function isPoomsae(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'poomsae';
    }

    // Label kategori pertandingan
    public function getKategoriLabelAttribute(): string
    {
        return ucfirst($this->kategori_pertandingan ?? '-');
    }

    // Label medali
    public function getMedaliLabelAttribute(): string
    {
        return match ($this->medali) {
            'emas' => 'Emas',
            'perak' => 'Perak',
            'perunggu' => 'Perunggu',
            default => 'Tidak Ada',
        };
    }

    // Warna badge medali (bisa dipakai di Filament / Vue)
    public function getMedaliColorAttribute(): string
    {
        return match ($this->medali) {
            'emas' => 'success',
            'perak' => 'gray',
            'perunggu' => 'warning',
            default => 'secondary',
        };
    }

    // Format biodata singkat
    public function getBiodataAttribute(): string
    {
        return "{$this->nama_lengkap} ({$this->sabuk})";
    }
}
