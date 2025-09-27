<?php

namespace App\Exports;

use App\Models\EventUjian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DateTime;

class EventUjianSiswaExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected $eventUjian;

    public function __construct(EventUjian $eventUjian)
    {
        $this->eventUjian = $eventUjian;
    }

    public function collection()
    {
        return $this->eventUjian->siswa; // hanya siswa yang ikut ujian ini
    }

    public function headings(): array
    {
        return [
            ['DATA PESERTA UJIAN'],
            ['Tanggal Ujian : ' . ($this->eventUjian->tanggal_ujian ? (new DateTime($this->eventUjian->tanggal_ujian))->format('d/m/Y') : '-')],
            ['Lokasi        : ' . ($this->eventUjian->lokasi_ujian ?? '-')],
            [],
            [
                'NO',
                'NAMA SISWA',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'NO REGISTER',
                'GEUP/ DAN',
                'ALAMAT',
                'NOMOR HP',
                'SABUK SAAT INI',
                // 'SABUK BERIKUTNYA',
                'KETERANGAN',
            ],
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        return [
            $no, // ✅ nomor urut (bukan id siswa)
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir?->format('d/m/Y'),
            $siswa->no_register,   // ✅ dipakai untuk import
            $siswa->geup_dan,
            $siswa->alamat,
            $siswa->nomor_telpon,
            $siswa->pivot->current_belt_level,
            $siswa->pivot->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Merge cell untuk judul
        $sheet->mergeCells('A1:J1');

        // Border semua data
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A5:{$highestColumn}{$highestRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto width kolom
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
