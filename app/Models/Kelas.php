<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'image',
        'name',
        'description',
        'kuota',
        'kuota_awal',
    ];

    public function getSisaKuotaAttribute()
{
    $terpakai = $this->siswa()->count();
    return max($this->kuota - $terpakai, 0);
}

    public function siswa()
{
    return $this->hasMany(Siswa::class);
}

public function kejuaraanSiswa()
{
    return $this->hasMany(\App\Models\KejuaraanSiswa::class, 'kelas_id');
}
public function resetSemuaKuotaSiswa()
{
    foreach ($this->siswas as $siswa) {
        $siswa->resetKuota();
    }
}



   
}
