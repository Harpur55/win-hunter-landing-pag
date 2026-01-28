<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Siswa;
use App\Models\UjianSiswa;
use App\Models\KejuaraanSiswa;


class Sertifikat extends Model
{
    protected $table = 'sertifikat';

    protected $fillable = [
        'event_ujian_siswa_id',
        'kejuaraan_siswa_id',
        'siswa_id',
        'no_sertifikat',
        'no_register',
        'nama_lengkap',
        'tanggal_lahir',
        'tanggal_ujian',
        'current_belt_level',
        'next_belt_level',
        'file_pdf',
        'generated_at',
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_ujian' => 'date',
        'is_active'     => 'boolean',
    ];

   protected static function booted()
{
    static::creating(function ($sertifikat) {
        if (
            ! $sertifikat->event_ujian_siswa_id &&
            ! $sertifikat->kejuaraan_siswa_id
        ) {
            throw new \Exception(
                'Sertifikat harus berasal dari ujian atau kejuaraan'
            );
        }

        if (
            $sertifikat->event_ujian_siswa_id &&
            $sertifikat->kejuaraan_siswa_id
        ) {
            throw new \Exception(
                'Sertifikat tidak boleh punya dua sumber'
            );
        }
    });
}

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function eventUjianSiswa(): BelongsTo
    {
        return $this->belongsTo(UjianSiswa::class, 'event_ujian_siswa_id');
    }

   
    public function getBeltProgressAttribute(): string
    {
        return "{$this->current_belt_level} → {$this->next_belt_level}";
    }

    public function getFileUrlAttribute(): ?string
{
    if (!$this->file_pdf) {
        return null;
    }

    return asset('storage/' . $this->file_pdf);
}

public function kejuaraanSiswa()
{
    return $this->belongsTo(
        \App\Models\KejuaraanSiswa::class,
        'kejuaraan_siswa_id'
    );
}

}
