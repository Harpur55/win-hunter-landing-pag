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
        'tingkat_kategori', // Beginner / Advance (kalau poomsae)
        'kategori_atlit',   // pracadet, cadet, junior, senior
        'berat_badan',
        'tinggi_badan',
        'medali',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔁 Event Boot - Otomatis Update Kuota Siswa
    |--------------------------------------------------------------------------
    */
  protected static function booted()
{
    // 🔹 Saat siswa mendaftar kejuaraan
    static::creating(function ($record) {
        $siswa = $record->siswa;

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        // ✅ Set periode otomatis ke tahun berjalan
        $record->periode = now()->year;

        // ✅ Cegah pendaftaran jika kuota habis
        if ($siswa->sisa_kuota <= 0) {
            throw new \Exception('Kuota siswa sudah habis. Tidak dapat mendaftar kejuaraan lagi.');
        }

        // ✅ Pastikan tidak melebihi batas kuota di tahun yang sama
        $jumlahTahunIni = self::where('siswa_id', $siswa->id)
            ->where('periode', now()->year)
            ->count();

        if ($jumlahTahunIni >= $siswa->kelas->kuota_awal) {
            throw new \Exception('Kuota siswa sudah habis untuk tahun ini.');
        }

        // ✅ Kurangi kuota hanya jika berhasil
        $siswa->decrement('sisa_kuota', 1);
    });

    // 🔹 Saat siswa batal ikut kejuaraan
    static::deleted(function ($record) {
        $siswa = $record->siswa;

        if ($siswa) {
            // ✅ Tambah kuota kembali
            $siswa->increment('sisa_kuota', 1);
        }
    });
}




    /*
    |--------------------------------------------------------------------------
    | 🔗 Relationships
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

    public function Kuotakejuaraan()
    {
        return $this->belongsTo(\App\Models\Kejuaraan::class, 'kejuaraan_id');
    }

    /*
    |--------------------------------------------------------------------------
    | 🧩 Helper Functions & Accessors
    |--------------------------------------------------------------------------
    */
    public function isKyorugi(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'kyorugi';
    }

    public function isPoomsae(): bool
    {
        return strtolower($this->kategori_pertandingan ?? '') === 'poomsae';
    }

    public function getKategoriLabelAttribute(): string
    {
        return ucfirst($this->kategori_pertandingan ?? '-');
    }

    public function getKategoriColorAttribute(): string
    {
        return match (strtolower($this->kategori_pertandingan ?? '')) {
            'kyorugi' => 'primary',
            'poomsae' => 'info',
            default   => 'secondary',
        };
    }

    public function getMedaliLabelAttribute(): string
    {
        return match ($this->medali) {
            'emas' => 'Emas',
            'perak' => 'Perak',
            'perunggu' => 'Perunggu',
            default => 'Tidak Ada',
        };
    }

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

    /*
    |--------------------------------------------------------------------------
    | 🧠 Helper: Cek Apakah Siswa Sudah Terdaftar
    |--------------------------------------------------------------------------
    */
    public static function sudahTerdaftar($kejuaraanId, $siswaId, $kategori)
    {
        return self::where('kejuaraan_id', $kejuaraanId)
            ->where('siswa_id', $siswaId)
            ->where('kategori_pertandingan', $kategori)
            ->exists();
    }
}
