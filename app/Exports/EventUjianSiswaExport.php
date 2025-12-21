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
                'ALAMAT',
                'NOMOR HP',
                'SABUK SAAT INI',
                'GEUP/ DAN',
                'SABUK BERIKUTNYA',
                'KETERANGAN',
            ],
        ];
    }

   public function map($siswa): array
{
    static $no = 0;
    $no++;

    $currentBelt = strtolower($siswa->pivot->current_belt_level ?? '');
    $nextBelt = $siswa->pivot->next_belt_level ?? '-';

    return [
        $no,
        $siswa->nama_lengkap,
        $siswa->tempat_lahir,
        $siswa->tanggal_lahir?->format('d/m/Y'),
        $siswa->no_register,
        $siswa->alamat,
        $siswa->nomor_telpon,
        $siswa->pivot->current_belt_level,       // Sabuk saat ini
        self::beltToGeup($currentBelt),          // Geup / Dan dari sabuk saat ini
        $nextBelt,                               // Sabuk berikutnya
        $siswa->pivot->keterangan,
    ];
}


    public function styles(Worksheet $sheet)
    {
        // Bold judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        
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

    private static function beltToGeup(?string $belt): string
{
    $mapping = [
        'putih'               => '10 Geup',
        'kuning'              => '9 Geup',
        'kuning strip hijau'  => '8 Geup',
        'hijau'               => '7 Geup',
        'hijau strip biru'    => '6 Geup',
        'biru'                => '5 Geup',
        'biru strip merah'    => '4 Geup',
        'merah'               => '3 Geup',
        'merah strip hitam 1' => '2 Geup',
        'merah strip hitam 2' => '1 Geup',
        'hitam'               => '1 Dan',
    ];

    return $mapping[$belt] ?? '-';
}

}
