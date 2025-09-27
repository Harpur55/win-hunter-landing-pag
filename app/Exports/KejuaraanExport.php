<?php

namespace App\Exports;

use App\Models\Kejuaraan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DateTime;

class KejuaraanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $event;
    private int $rowNumber = 0;

    public function __construct(Kejuaraan $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event->siswa; // ambil hanya siswa yg ikut event ini
    }

    public function headings(): array
    {
        return [
            ['DATA PESERTA KEJUARAAN'],
            ['Tanggal : ' . ($this->event->tanggal_mulai ? (new DateTime($this->event->tanggal_event))->format('d/m/Y') : '-')],
            ['Lokasi  : ' . ($this->event->lokasi ?? '-')],
            ['Nama Kejuaraan :'. ($this->event->nama_kejuaraan ?? '-')],
            [
                'NO',
                'NAMA SISWA',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'JENIS KELAMIN',
                'SABUK',
                'KATEGORI',
                'BERAT BADAN',
                'TINGGI BADAN',
                'KELOMPOK USIA',
                'MEDALI',
            ],
        ];
    }

    public function map($siswa): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d/m/Y') : '-',
            $siswa->jenis_kelamin,
            $siswa->current_belt_level,
            $siswa->pivot->kategori_pertandingan,
            $siswa->pivot->berat_badan,
            $siswa->pivot->tinggi_badan,
            $siswa->pivot->kategori_atlit,
            ucfirst($siswa->pivot->medali),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Judul tebal
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        // Header tabel tebal
        $sheet->getStyle('A5:' . $highestColumn . '5')->getFont()->setBold(true);

        // Border semua data
        $sheet->getStyle('A5:' . $highestColumn . $highestRow)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}
