<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class SiswaExport implements FromCollection, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths, WithStartRow, WithStrictNullComparison
{
    /**
     * Ambil semua data siswa
     */
    public function collection()
    {
        return Siswa::with(['unit', 'kelas'])->get(); // eager load relasi biar efisien
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NOMOR REGISTRASI',
            'NAMA LENGKAP',
            'JENIS KELAMIN',
            'UNIT LATIHAN',
            'KELAS',
            'SABUK',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'GOLONGAN DARAH',
            'FOTO SISWA',
            'ALAMAT LENGKAP',
            'NOMOR TELEPON',
            'NAMA AYAH',
            'PEKERJAAN AYAH',
            'NAMA IBU',
            'PEKERJAAN IBU',
            'STATUS',
            'TANGGAL BERGABUNG',
        ];
    }

    /**
     * Mapping data siswa ke kolom
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->no_register,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->unit ? $siswa->unit->name : null,
            $siswa->kelas ? $siswa->kelas->name : null,
            $siswa->current_belt_level,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : null,
            $siswa->golongan_darah,
            $siswa->image,
            $siswa->alamat_lengkap,
            $siswa->no_telepon,
            $siswa->nama_ayah,
            $siswa->pekerjaan_ayah,
            $siswa->nama_ibu,
            $siswa->pekerjaan_ibu,
            $siswa->status,
            $siswa->joint_date ? Carbon::parse($siswa->joint_date)->format('Y-m-d') : null,
        ];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 18,
            'C' => 30,
            'D' => 18,
            'E' => 20,
            'F' => 18,
            'G' => 18,
            'H' => 30,
            'I' => 20,
            'J' => 10,
            'K' => 15,
            'L' => 40,
            'M' => 20,
            'N' => 25,
            'O' => 25,
            'P' => 25,
            'Q' => 25,
            'R' => 15,
            'S' => 20,
        ];
    }

    /**
     * Baris mulai data
     */
    public function startRow(): int
    {
        return 5;
    }

    /**
     * Styling worksheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->fromArray($this->headings(), null, 'A4');

        $numColumns = count($this->headings());
        $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($numColumns);

        // Judul utama
        $sheet->setCellValue('A1', 'FORMULIR PENDAFTARAN SISWA WIN-HUNTER');
        $sheet->mergeCells('A1:' . $lastColumnLetter . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Periode
        $sheet->setCellValue('A2', 'PERIODE : ' . strtoupper(Carbon::now()->format('F')));
        $sheet->mergeCells('A2:' . $lastColumnLetter . '2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Tahun
        $sheet->setCellValue('A3', 'TAHUN : ' . Carbon::now()->format('Y'));
        $sheet->mergeCells('A3:' . $lastColumnLetter . '3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header kolom (baris 4)
        $headerRow = 4;
        $headerRange = 'A' . $headerRow . ':' . $lastColumnLetter . $headerRow;
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF000000']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFCCCCCC'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Data cells styling
        $dataStartRow = $headerRow + 1;
        $lastDataRow = $sheet->getHighestRow();
        $dataRange = 'A' . $dataStartRow . ':' . $lastColumnLetter . $lastDataRow;
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Kolom NIS rata tengah
        $sheet->getStyle('A' . $dataStartRow . ':' . 'A' . $lastDataRow)
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return $sheet;
    }
}
