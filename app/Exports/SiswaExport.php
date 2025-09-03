<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

class SiswaExport implements 
    FromCollection,
    WithMapping,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithColumnWidths,
    WithStrictNullComparison
{
    private int $rowNumber = 0; // untuk nomor urut

    public function collection()
    {
        return Siswa::with(['unit', 'kelas'])->get();
    }

    public function headings(): array
    {
        return [
            'NO',                 // ✅ kolom nomor urut
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

    public function map($siswa): array
    {
        $this->rowNumber++; // increment tiap row
        return [
            $this->rowNumber,   // ✅ nomor urut
            $siswa->nis,
            $siswa->no_register,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->unit?->name,
            $siswa->kelas?->name,
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

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // NO
            'B' => 12,  // NIS
            'C' => 20,
            'D' => 25,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 15,
            'I' => 20,
            'J' => 15,
            'K' => 15,
            'L' => 30,
            'M' => 40,
            'N' => 20,
            'O' => 25,
            'P' => 25,
            'Q' => 25,
            'R' => 25,
            'S' => 15,
            'T' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $numColumns = count($this->headings());
        $lastColumnLetter = Coordinate::stringFromColumnIndex($numColumns);

        // Judul besar
        $sheet->insertNewRowBefore(1, 3);
        $sheet->setCellValue('A1', 'FORMULIR PENDAFTARAN SISWA WIN-HUNTER');
        $sheet->mergeCells("A1:{$lastColumnLetter}1");
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->setCellValue('A2', 'PERIODE : ' . strtoupper(Carbon::now()->format('F')));
        $sheet->mergeCells("A2:{$lastColumnLetter}2");
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->setCellValue('A3', 'TAHUN : ' . Carbon::now()->format('Y'));
        $sheet->mergeCells("A3:{$lastColumnLetter}3");
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header tabel (baris 4)
        $headerRange = "A4:{$lastColumnLetter}4";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFCCCCCC'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }
}