<?php

namespace App\Exports;

use App\Models\EventUjian;
use App\Models\UjianSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DateTime;

class EventUjianSiswaExport implements FromCollection, WithHeadings, WithStyles, WithMapping,  WithColumnFormatting
{
    protected $eventUjian;

    public function __construct(EventUjian $eventUjian)
{
    // 🔥 pastikan ambil data FULL dari DB
    $this->eventUjian = EventUjian::find($eventUjian->id);
}

    // 🔥 AMBIL DARI PIVOT (UjianSiswa)
    public function collection()
    {
        return UjianSiswa::with('siswa')
            ->where('event_ujian_id', $this->eventUjian->id)
            ->get();
    }

    public function headings(): array
    {
        return [
            ['DATA PESERTA UJIAN',($this->eventUjian->nama_ujian ?? '-')],
            ['Tanggal Ujian : ' . ($this->eventUjian->tanggal_ujian
                ? (new DateTime($this->eventUjian->tanggal_ujian))->format('d/m/Y')
                : '-')],
            ['Lokasi        : ' . ($this->eventUjian->lokasi_ujian ?? '-')],
            ['Jumlah Peserta : ' . $this->eventUjian->ujianSiswa()->count()],
            [
                'NO',
                'NAMA SISWA',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'NO REGISTER',
                'ALAMAT',
                'NOMOR HP',
                'SABUK SAAT INI',
                'GEUP / DAN',
                'SABUK BERIKUTNYA',
                'KETERANGAN',
            ],
        ];
    }

    // 🔥 MAP DARI MODEL UjianSiswa
    public function map($ujianSiswa): array
    {
        static $no = 0;
        $no++;

        $siswa = $ujianSiswa->siswa;

        $currentBelt = strtolower($ujianSiswa->current_belt_level ?? '');
        $nextBelt = $ujianSiswa->next_belt_level ?? '-';

        return [
            $no,
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir?->format('d/m/Y'),
           (string) $siswa->no_register,

            // ✅ AUTO DECRYPT (ACCESSOR MODEL SISWA)
            $siswa->alamat_lengkap,
           (string) $siswa->no_telpon,

            $ujianSiswa->current_belt_level,
            self::beltToGeup($currentBelt),
            $nextBelt,
            $ujianSiswa->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:J1');

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A5:{$highestColumn}{$highestRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

        public function columnFormats(): array
{
    return [
        'E' => NumberFormat::FORMAT_TEXT, // NO REGISTER
        'G' => NumberFormat::FORMAT_TEXT, // NOMOR HP (sekalian biar aman)
    ];
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
