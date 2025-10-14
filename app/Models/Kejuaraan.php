<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kejuaraan extends Model
{
    //
    protected $table = 'kejuaraans';
    protected $fillable = [
       'nama_kejuaraan','tanggal_mulai','tanggal_selesai','lokasi'
    ];

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
                'kategori_atlit',
                'berat_badan',
                'tinggi_badan',
                'medali',
            ])
            ->withTimestamps();
    }

public function sisaKuota(): int
{
    $terdaftar = $this->siswa()->count();
    return max(0, $this->kuota - $terdaftar);
}

    public function sisaKuotaReguler(): int
{
    $terdaftar = $this->siswa()->wherePivot('kategori', 'reguler')->count();
    return max(0, $this->kuota_reguler - $terdaftar);
}

public function sisaKuotaPrestasi(): int
{
    $terdaftar = $this->siswa()->wherePivot('kategori', 'prestasi')->count();
    return max(0, $this->kuota_prestasi - $terdaftar);
}

public function sisaKuotaKhusus(): int
{
    $terdaftar = $this->siswa()->wherePivot('kategori', 'khusus')->count();
    return max(0, $this->kuota_khusus - $terdaftar);
    

}
public function sisaKuotaKelasPoomsae(): int
{
    $terdaftar = $this->siswa()->wherePivot('kategori', 'kelas_poomsae')->count();
    return max(0, $this->kuota_kelas_poomsae - $terdaftar);

}
}