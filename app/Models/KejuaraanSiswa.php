<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'kategori_pertandingan', // kyorugi / poomsae
        'tageuk',
        'tingkat_kategori', // Beginer / Advance (kalau poomsae)
        'kategori_atlit',   // pracadet, cadet, junior, senior
        'berat_badan',
        'tinggi_badan',
        'medali',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function kejuaraan(): BelongsTo
    {
        return $this->belongsTo(Kejuaraan::class, 'kejuaraan_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers & Accessors
    |--------------------------------------------------------------------------
    */

    // Apakah kategori pertandingan Kyorugi
    public function isKyorugi(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'kyorugi';
    }

    // Apakah kategori pertandingan Poomsae
    public function isPoomsae(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'poomsae';
    }

    // Label kategori pertandingan
    public function getKategoriLabelAttribute(): string
    {
        return ucfirst($this->kategori_pertandingan ?? '-');
    }

    // Warna kategori (untuk badge)
    public function getKategoriColorAttribute(): string
    {
        return match (strtolower($this->kategori_pertandingan ?? '')) {
            'kyorugi' => 'primary',
            'poomsae' => 'info',
            default   => 'secondary',
        };
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

    // Warna medali (untuk badge)
    public function getMedaliColorAttribute(): string
    {
        return match ($this->medali) {
            'emas' => 'success',
            'perak' => 'gray',
            'perunggu' => 'warning',
            default => 'secondary',
        };
    }

    
    public function getBiodataAttribute(): string
    {
        return "{$this->nama_lengkap} ({$this->sabuk})";
    }

    
    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->age : null;
    }

    
    public function getKategoriFullLabelAttribute(): string
    {
        return ucfirst($this->kategori_pertandingan) . ' - ' . ucfirst($this->kategori_atlit);
    }
    public static function sudahTerdaftar($kejuaraanId, $siswaId, $kategori)
{
    return self::where('kejuaraan_id', $kejuaraanId)
        ->where('siswa_id', $siswaId)
        ->where('kategori_pertandingan', $kategori)
        ->exists();
}
}
