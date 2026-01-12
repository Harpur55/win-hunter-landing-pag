<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaCuti extends Model
{
    protected $fillable = [
        'siswa_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'keterangan',
        'status',
        'approved_by',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
