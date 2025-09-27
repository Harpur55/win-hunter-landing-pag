<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    public function collection()
    {
        return Siswa::with(['unit', 'kelas'])
            ->get()



            ->map(function ($siswa) {
                return [
                    'nama_lengkap'   => $siswa->nama_lengkap,
                    'jenis_kelamin'  => $siswa->jenis_kelamin,
                    'unit_latihan'   => optional($siswa->unit)->name,
                    'kelas'          => optional($siswa->kelas)->name,
                    'sabuk'          => $siswa->current_belt_level, // langsung simpan sesuai DB
                    'geup'            => $siswa->geup_dan,
                    'beladiri'       => $siswa->beladiri_yang_pernah_diikuti,
                    'tempat_lahir'   => $siswa->tempat_lahir,
                    'tanggal_lahir'  => $siswa->tanggal_lahir?->toDateString(),
                    'golongan_darah' => $siswa->golongan_darah,
                    'alamat'         => $siswa->alamat_lengkap,
                    'no_telepon'     => $siswa->no_telepon,
                    'nama_ayah'      => $siswa->nama_ayah,
                    'pekerjaan_ayah' => $siswa->pekerjaan_ayah,
                    'nama_ibu'       => $siswa->nama_ibu,
                    'pekerjaan_ibu'  => $siswa->pekerjaan_ibu,
                    'status'         => $siswa->status,
                    'tanggal_gabung' => $siswa->joint_date?->toDateString(),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Jenis Kelamin',
            'Unit Latihan',
            'Kelas',
            'Sabuk',
            'Geup/ Dan',
            'Beladiri yang Pernah Diikuti',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Golongan Darah',
            'Alamat',
            'No Telepon',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Status',
            'Tanggal Bergabung',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Tanggal Lahir
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Tanggal Bergabung
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $event->sheet->insertNewRowBefore(1, 1);
    //             $event->sheet->setCellValue('A1', 'Data Siswa Win Hunter ' . now()->format('F Y'));

    //             $event->sheet->mergeCells('A1:Q1');
    //             $event->sheet->getStyle('A1')->applyFromArray([
    //                 'font' => [
    //                     'bold' => true,
    //                     'size' => 14,
    //                 ],
    //                 'alignment' => [
    //                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 ],
    //             ]);
    //         },
    //     ];
    // }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul di baris pertama
                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Data Siswa Win Hunter ' . now()->format('F Y'));
                $sheet->mergeCells('A1:Q1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Tentukan range header (misalnya row ke-2 untuk heading)
                $headerRange = 'A2:Q2';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'], // abu2 muda
                    ],
                ]);

                // Styling untuk isi tabel (mulai baris 3 sampai terakhir)
                $lastRow = $sheet->getHighestRow();
                $tableRange = 'A2:Q' . $lastRow;
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Auto-size setiap kolom agar menyesuaikan isi
                foreach (range('A', 'Q') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
