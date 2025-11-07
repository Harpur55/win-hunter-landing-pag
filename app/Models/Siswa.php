<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class Siswa extends Model
{
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
        'sisa_kuota', // ✅ pastikan kolom ini sudah ditambahkan di tabel siswas
        'current_belt_level',
        'beladiri_yang_pernah_diikuti',
        'joint_date',
        'status',
        'units_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'joint_date'    => 'date',
    ];

    protected $table = 'siswas';

    protected static function boot()
    {
        parent::boot();

        // Auto generate NIS
        static::creating(function ($siswa) {
            if (empty($siswa->nis)) {
                $lastSiswa   = static::orderByDesc('id')->first();
                $lastNis     = $lastSiswa ? $lastSiswa->nis : null;
                $lastNumber  = $lastNis ? (int) str_replace('WH-', '', $lastNis) : 0;
                $siswa->nis  = 'WH-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }

            // ✅ Set sisa_kuota awal sesuai kelas
            if ($siswa->kelas && isset($siswa->kelas->kuota_awal)) {
                $siswa->sisa_kuota = $siswa->kelas->kuota_awal;
            }
        });
    }

    // =======================
    // 🔐 Enkripsi Data Sensitif
    // =======================

    protected function alamatLengkap(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    protected function noTelepon(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    protected function namaAyah(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    protected function namaIbu(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Crypt::decryptString($value) : null,
            set: fn($value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    // =======================
    // 🔗 Relasi & Custom Attribute
    // =======================

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
            ->withPivot(['current_belt_level', 'next_belt_level', 'geup', 'keterangan'])
            ->withTimestamps();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kejuaraan()
    {
        return $this->belongsToMany(\App\Models\Kejuaraan::class, 'kejuaraan_siswa', 'siswa_id', 'kejuaraan_id')
            ->withPivot(['medali'])
            ->withTimestamps();
    }

    // =======================
    // 🔧 Helper Function
    // =======================

    public function resetKuota(): void
    {
        if ($this->kelas && $this->kelas->kuota_awal !== null) {
            $this->update(['sisa_kuota' => $this->kelas->kuota_awal]);
        }
    }

    public function kurangiKuota(): void
    {
        if ($this->sisa_kuota > 0) {
            $this->decrement('sisa_kuota');
        }
    }

    public function tambahKuota(): void
    {
        if ($this->kelas && $this->sisa_kuota < $this->kelas->kuota_awal) {
            $this->increment('sisa_kuota');
        }
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function model(array $row)
    {
        return new Siswa([
            'nis' => $row['nis'] ?? null,
            'no_register' => $row['nomor_registrasi'] ?? null,
            'nama_lengkap' => $row['nama_lengkap'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'units_id' => !empty($row['unit_latihan']) ? Unit::where('name', $row['unit_latihan'])->value('id') : null,
            'kelas_id' => !empty($row['kelas']) ? Kelas::where('name', $row['kelas'])->value('id') : null,
            'sisa_kuota' => !empty($row['kelas']) ? (Kelas::where('name', $row['kelas'])->value('kuota_awal') ?? 0) : 0,
            'current_belt_level' => $row['sabuk'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => !empty($row['tanggal_lahir']) ? Carbon::parse($row['tanggal_lahir']) : null,
            'golongan_darah' => $row['golongan_darah'] ?? null,
            'image' => $row['foto_siswa'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            'no_telepon' => $row['nomor_telepon'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'pekerjaan_ayah' => $row['pekerjaan_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'pekerjaan_ibu' => $row['pekerjaan_ibu'] ?? null,
            'status' => $row['status'] ?? 'Aktif',
            'joint_date' => !empty($row['tanggal_bergabung']) ? Carbon::parse($row['tanggal_bergabung']) : null,
            'beladiri_yang_pernah_diikuti' => $row['beladiri_yang_pernah_diikuti'] ?? null,
        ]);
    }

    public function getGeupDanAttribute()
    {
        $belt = strtolower(trim($this->current_belt_level ?? ''));
        $geupMap = [
            'putih' => 10,
            'kuning' => 9,
            'kuning strip hijau' => 8,
            'hijau' => 7,
            'hijau strip biru' => 6,
            'biru' => 5,
            'biru strip merah' => 4,
            'merah' => 3,
            'merah strip hitam 1' => 2,
            'merah strip hitam 2' => 1,
        ];

        if (preg_match('/hitam.*dan\s*(\d+)/i', $belt, $matches)) {
            return 'Dan ' . (int) $matches[1];
        }

        return $geupMap[$belt] ?? null;
    }
}
