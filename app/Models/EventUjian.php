<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUjian extends Model
{
    protected $table = 'event_ujian';
    protected $fillable = [
        'nama_ujian',
        'tanggal_ujian',
        'lokasi_ujian',
    ];
       public function siswas()
    {
        // Pastikan nama pivot table 'event_ujian_siswa' dan foreign key sesuai
        return $this->belongsToMany(Siswa::class, 'data_ujian', 'event_ujian_id', 'siswa_id')
                    ->withPivot('keterangan'); // Tambahkan kolom pivot jika perlu
    }
    //
}
