<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        // 'lookup_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'joint_date'    => 'date',
    ];

    protected $table = 'siswas';

    protected static function boot()
    {
        parent::boot();

        //  static::creating(function ($siswa) {
        //     if (empty($siswa->lookup_token)) {
        //         $siswa->lookup_token = (string) Str::uuid();
        //     }
        //     if (empty($siswa->current_belt_level)) {
        //         $siswa->current_belt_level = 'Putih';
        //     }
        //     if (empty($siswa->status)) {
        //         $siswa->status = 'Aktif';
        //     }
        // });

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

        // Saat siswa berpindah kelas
        static::updating(function ($siswa) {
            if ($siswa->isDirty('kelas_id')) {
                if ($siswa->kelas && isset($siswa->kelas->kuota_awal)) {
                    $siswa->sisa_kuota = $siswa->kelas->kuota_awal;
                }
            }
        });

        // Saat kejuaraan siswa berubah, sinkron ulang kuota
        static::saved(function ($siswa) {
            $siswa->syncSisaKuota();
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
         return $this->belongsToMany(Kejuaraan::class, 'kejuaraan_siswa', 'siswa_id', 'kejuaraan_id')
            ->withPivot([
                'nama_lengkap',
                'kategori_pertandingan',
                'kategori_atlit',
                'medali',
                'sabuk',
                'status',
            ])
            ->withTimestamps();
    }

    // =======================
    // 🔧 Helper Function
    // =======================

      public function kuotaTerpakai(): int
    {
        return $this->kejuaraan()->count();
    }

 public function resetKuota(): void
{
     $kuotaAwal = $this->kelas?->kuota_awal ?? 0;
    $this->update([
        'sisa_kuota' => max(0, $kuotaAwal),
    ]);
}


    public function kurangiKuota(): void
    {
       if ($this->sisa_kuota > 0) {
        $this->decrement('sisa_kuota');
    } else {
        // pastikan tetap 0 (tidak minus)
        $this->update(['sisa_kuota' => 0]);
    }
    }

    public function tambahKuota(): void
    {
        if ($this->kelas && $this->sisa_kuota < $this->kelas->kuota_awal) {
            $this->increment('sisa_kuota');
        }
    }

      public function sisaKuota(): int
    {
            return max(0, (int) $this->sisa_kuota); // selalu minimal 0

    }

     public function syncSisaKuota(): void
    {
        $this->updateQuietly([
            'sisa_kuota' => $this->sisaKuota(),
        ]);
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


      public static function monitorKuota(): array
    {
        return static::select('kelas_id',
            DB::raw('COUNT(*) as total_siswa'),
            DB::raw('SUM(sisa_kuota) as total_sisa'),
            DB::raw('AVG(sisa_kuota) as rata_rata')
        )
        ->groupBy('kelas_id')
        ->with('kelas:id,nama,kuota_awal')
        ->get()
        ->map(function ($item) {
            return [
                'kelas' => $item->kelas->nama ?? '-',
                'kuota_awal' => $item->kelas->kuota_awal ?? 0,
                'total_siswa' => $item->total_siswa,
                'total_sisa' => $item->total_sisa,
                'rata_rata_sisa' => round($item->rata_rata, 1),
            ];
        })
        ->toArray();
    }
}
