<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kejuaraan extends Model
{
    protected $table = 'kejuaraans';

    protected $fillable = [
        'nama_kejuaraan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'is_registration_closed',
        'kuota_reguler',
        'kuota_prestasi',
        'kuota_khusus',
        'kuota_kelas_poomsae',
        'slug',
    ];

    /**
     * Relasi ke siswa yang mengikuti kejuaraan.
     */
    protected static function booted()
    {
        static::creating(function ($kejuaraan) {
            if (empty($kejuaraan->slug)) {
                $baseSlug = Str::slug($kejuaraan->nama_kejuaraan);
                $slug = $baseSlug;
                $count = 1;

                // pastikan unik
                while (self::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                $kejuaraan->slug = $slug;
            }
        });
    }
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'kejuaraan_siswa', 'kejuaraan_id', 'siswa_id')
            ->withPivot([
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'sabuk',
                'kategori_pertandingan',
                'tageuk',
                'tingkat_kategori',
                'kategori_atlit', // kolom kategori sebenarnya
                'berat_badan',
                'tinggi_badan',
                'status',
                'medali',
            ])
            ->withTimestamps();
    }

    /**
     * Hitung total kuota kejuaraan (semua kategori digabung).
     */
    public function totalKuota(): int
    {
        return ($this->kuota_reguler ?? 0)
            + ($this->kuota_prestasi ?? 0)
            + ($this->kuota_khusus ?? 0)
            + ($this->kuota_kelas_poomsae ?? 0);
    }

    /**
     * Hitung sisa kuota total (semua kategori).
     */
    public function sisaKuota(): int
    {
        $terdaftar = $this->siswa()->count();
        return max(0, $this->totalKuota() - $terdaftar);
    }

    /**
     * Kuota kategori Reguler.
     */
    public function sisaKuotaReguler(): int
    {
        $terdaftar = $this->siswa()->wherePivot('kategori_atlit', 'reguler')->count();
        return max(0, ($this->kuota_reguler ?? 0) - $terdaftar);
    }

    /**
     * Kuota kategori Prestasi.
     */
    public function sisaKuotaPrestasi(): int
    {
        $terdaftar = $this->siswa()->wherePivot('kategori_atlit', 'prestasi')->count();
        return max(0, ($this->kuota_prestasi ?? 0) - $terdaftar);
    }

    /**
     * Kuota kategori Khusus.
     */
    public function sisaKuotaKhusus(): int
    {
        $terdaftar = $this->siswa()->wherePivot('kategori_atlit', 'khusus')->count();
        return max(0, ($this->kuota_khusus ?? 0) - $terdaftar);
    }

    /**
     * Kuota kategori Kelas Poomsae.
     */
    public function sisaKuotaKelasPoomsae(): int
    {
        $terdaftar = $this->siswa()->wherePivot('kategori_atlit', 'kelas_poomsae')->count();
        return max(0, ($this->kuota_kelas_poomsae ?? 0) - $terdaftar);
    }

    /**
     * Hitung total terpakai semua kategori.
     */
    public function kuotaTerpakai(): int
    {
        return $this->siswa()->count();
    }

    /**
     * Format data ringkas untuk monitoring / dashboard.
     */
    public function getSummaryAttribute()
    {
        return [
            'total_kuota'   => $this->totalKuota(),
            'terpakai'      => $this->kuotaTerpakai(),
            'sisa'          => $this->sisaKuota(),
            'reguler'       => $this->sisaKuotaReguler(),
            'prestasi'      => $this->sisaKuotaPrestasi(),
            'khusus'        => $this->sisaKuotaKhusus(),
            'kelas_poomsae' => $this->sisaKuotaKelasPoomsae(),
        ];
    }
}
