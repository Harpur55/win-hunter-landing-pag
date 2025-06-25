<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SiswaImport implements ToModel, WithHeadingRow

{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tanggalLahir = isset($row['tanggal_lahir']) ? \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d') : null;
        $jointDate = isset($row['tanggal_bergabung']) ? \Carbon\Carbon::parse($row['tanggal_bergabung'])->format('Y-m-d') : null;
        return new Siswa([
            //
            'no_register' => $row['no_register'] ?? null,
            'nis' => $row['nis'] ?? null,
            'nama_lengkap' => $row['nama_lengkap'] ?? null,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $tanggalLahir,
            'golongan_darah' => $row['golongan_darah'] ?? null,
            'image' => $row['image'] ?? null,
            'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
            'no_telepon' => $row['no_telepon'] ?? null,
            'nama_ayah' => $row['nama_ayah'] ?? null,
            'pekerjaan_ayah' => $row['pekerjaan_ayah'] ?? null,
            'nama_ibu' => $row['nama_ibu'] ?? null,
            'pekerjaan_ibu' => $row['pekerjaan_ibu'] ?? null,
            'unit_latihan' => $row['unit_latihan'] ?? null,
            'kelas' => $row['kelas'] ?? null,
            'sabuk' => $row['sabuk'] ?? null,
            'joint_date' => $jointDate,
            'status' => $row['status'] ?? 'Aktif',
           
        ]);
    }
}
