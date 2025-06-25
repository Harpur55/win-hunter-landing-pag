<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Siswa::all();
    }
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nis',
            'No Register',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Golongan Darah',
            'Image',
            'Unit Latihan',
            'Kelas',
            'Sabuk',
            'Alamat Lengkap',
            'No Telepon',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Status',
            'Joint Date',
        ];
    }
    /**
     * @param Siswa $siswa
     * @return array
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->no_register,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : null,
            $siswa->golongan_darah,
            $siswa->image,
            $siswa->unit_latihan,
            $siswa->kelas,
            $siswa->sabuk,
            $siswa->alamat_lengkap,
            $siswa->no_telepon,
            $siswa->nama_ayah,
            $siswa->pekerjaan_ayah,
            $siswa->nama_ibu,
            $siswa->pekerjaan_ibu,
            $siswa->status,
            $siswa->joint_date ? $siswa->joint_date->format('Y-m-d') : null,
        ];
    }
}
