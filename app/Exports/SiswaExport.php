<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiswaExport extends DefaultValueBinder
implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting, WithCustomValueBinder
{
    public function collection()
    {
        return Siswa::with(['unit', 'kelas'])
            ->get()
            ->map(function ($siswa) {
                return [
                    'nama_lengkap'   => $siswa->nama_lengkap,
                    'no_register'    => $siswa->no_register, // LOGIC TIDAK DIUBAH
                    'jenis_kelamin'  => $siswa->jenis_kelamin,
                    'unit_latihan'   => optional($siswa->unit)->name,
                    'kelas'          => optional($siswa->kelas)->name,
                    'sabuk'          => $siswa->current_belt_level,
                    'geup'           => $siswa->geup_dan,
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
            'No Register',
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
            'B' => NumberFormat::FORMAT_TEXT,               // No Register
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,      // Tanggal Lahir
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,      // Tanggal Bergabung
        ];
    }

    /**
     * 🔒 KUNCI TIPE DATA KOLOM B SEBAGAI STRING
     * TANPA MENGUBAH LOGIC DATA
     */
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'B' && $value !== null) {
            $cell->setValueExplicit(
                (string) $value,
                DataType::TYPE_STRING
            );
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Judul
                $sheet->insertNewRowBefore(1, 1);
                $sheet->setCellValue('A1', 'Data Siswa Win Hunter ' . now()->format('F Y'));
                $sheet->mergeCells('A1:Q1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Header
                $sheet->getStyle('A2:Q2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'],
                    ],
                ]);

                // Isi tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A2:Q{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                foreach (range('A', 'Q') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
