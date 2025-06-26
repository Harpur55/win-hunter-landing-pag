<?php

namespace App\Exports;

use App\Models\coach;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;



class CoachExport implements FromCollection , WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return coach::all();
    }
    public function headings(): array
    {
        return [
            'Foto',
            'Nama',
            'Sabuk',
            'Nomor Telepon',
            'Alamat',
            'role',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
    public function map($coach): array
    {
        return [
            $coach->foto,
            $coach->nama,
            $coach->sabuk,
            $coach->nomor_telepon,
            $coach->alamat,
            $coach->role,
            $coach->status,
            $coach->created_at ? $coach->created_at->format('Y-m-d H:i:s') : null,
            $coach->updated_at ? $coach->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

}
