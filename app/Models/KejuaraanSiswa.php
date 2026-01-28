<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Carbon\Carbon;

class KejuaraanSiswa extends Model
{
    use HasFactory;

    protected $table = 'kejuaraan_siswa';

    protected $fillable = [
        'kejuaraan_id',
        'siswa_id',
        'units_id',

        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'sabuk',

        'kategori_pertandingan',
        'kategori_atlit',

        'berat_badan',
        'tinggi_badan',
        'kelas_berat',

        'tageuk',
        'tingkat_kategori',

        'medali',
        'status',

        'use_kuota',
        'periode',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'use_kuota'     => 'boolean',
        'periode'       => 'integer',
    ];

  
    protected static function booted()
{
   

    
      static::creating(function (self $record) {

            /**
             * ❗ HANYA DEFAULT VALUE
             * ❌ TIDAK ADA LOGIC BISNIS
             */

            $record->periode ??= now()->year;
            $record->use_kuota ??= true;

             if ($record->tanggal_lahir) {
            $umur = Carbon::parse($record->tanggal_lahir)->age;

            $record->kategori_atlit = match (true) {
                $umur >= 6 && $umur <= 11 => 'pracadet',
                $umur >= 12 && $umur <= 14 => 'cadet',
                $umur >= 15 && $umur <= 17 => 'junior',
                $umur >= 18                => 'senior',
                default                    => null,
            };
        }
       
    });
}


   
    public function kejuaraan(): BelongsTo
    {
        return $this->belongsTo(Kejuaraan::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'units_id');
    }

    public function isKyorugi(): bool
    {
        return $this->kategori_pertandingan === 'kyorugi';
    }

    public function isPoomsae(): bool
    {
        return $this->kategori_pertandingan === 'poomsae';
    }

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir
            ? Carbon::parse($this->tanggal_lahir)->age
            : null;
    }

    public function getKategoriFullLabelAttribute(): string
    {
        return ucfirst($this->kategori_pertandingan)
            . ' - '
            . ucfirst($this->kategori_atlit ?? '-');
    }

    public function getMedaliLabelAttribute(): string
    {
        return match ($this->medali) {
            'emas'     => 'Emas',
            'perak'    => 'Perak',
            'perunggu' => 'Perunggu',
            default    => 'Tidak Ada',
        };
    }

    
    public static function sudahTerdaftar(
        int $kejuaraanId,
        int $siswaId,
        string $kategori
    ): bool {
        return self::query()
            ->where('kejuaraan_id', $kejuaraanId)
            ->where('siswa_id', $siswaId)
            ->where('kategori_pertandingan', $kategori)
            ->exists();
    }

   public function sertifikat()
{
    return $this->hasOne(\App\Models\Sertifikat::class);
}

}
