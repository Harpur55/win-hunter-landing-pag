<?php

namespace App\Exports;

use App\Models\Kejuaraan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KejuaraanSiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected Kejuaraan $event;
    protected int $no = 0;

    public function __construct(Kejuaraan $event)
    {
        $this->event = $event;
    }

    public function collection()
    {
        return $this->event->siswa()->withPivot([
            'nama_lengkap',
            'tempat_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'sabuk',
            'kategori_pertandingan',
            'tageuk',
            'tingkat_kategori',
            'kategori_atlit',
            'berat_badan',
            'tinggi_badan',
            'medali',
        ])->get();
    }

    public function headings(): array
    {
        $tanggal = $this->event->tanggal_mulai
            ? Carbon::parse($this->event->tanggal_mulai)->translatedFormat('d/m/Y')
            : '-';

        $rekap = $this->hitungRekap();

        return [
            ['DATA PESERTA KEJUARAAN'],
            ['Nama Kejuaraan : ' . ($this->event->nama_kejuaraan ?? '-')],
            ['Tanggal : ' . $tanggal],
            ['Total Peserta : ' . $rekap['peserta']],
            [
                'Rekap Medali :',
                'Emas: ' . $rekap['emas'],
                'Perak: ' . $rekap['perak'],
                'Perunggu: ' . $rekap['perunggu'],
                'Total: ' . $rekap['total'],
            ],
            [],
            [
                'NO',
                'NAMA SISWA',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'JENIS KELAMIN',
                'SABUK',
                'KATEGORI PERTANDINGAN',
                'TAEGEUK',
                'TINGKAT KATEGORI',
                'KELOMPOK USIA',
                'BERAT BADAN (KG)',
                'TINGGI BADAN (CM)',
                'MEDALI',
            ],
        ];
    }

    public function map($siswa): array
    {
        $this->no++;

        return [
            $this->no,
            $siswa->pivot->nama_lengkap ?? $siswa->nama_lengkap,
            $siswa->pivot->tempat_lahir ?? '-',
            $siswa->pivot->tanggal_lahir
                ? Carbon::parse($siswa->pivot->tanggal_lahir)->format('d/m/Y')
                : '-',
            $siswa->pivot->jenis_kelamin ?? '-',
            $siswa->pivot->sabuk ?? '-',
            $siswa->pivot->kategori_pertandingan ?? '-',
            $siswa->pivot->tageuk ?? '-',
            $siswa->pivot->tingkat_kategori ?? '-',
            $siswa->pivot->kategori_atlit ?? '-',
            $siswa->pivot->berat_badan ?? '-',
            $siswa->pivot->tinggi_badan ?? '-',
            ucfirst($siswa->pivot->medali ?? '-'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold dan ukuran judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Bold untuk metadata
        $sheet->getStyle('A2:A5')->getFont()->setBold(true);

        // Bold untuk header tabel
        $sheet->getStyle('A7:M7')->getFont()->setBold(true);

        // Border untuk header tabel
        $sheet->getStyle('A7:M7')->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Auto width semua kolom
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Rata tengah kolom tertentu
        $sheet->getStyle('A:M')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    protected function hitungRekap(): array
    {
        $emas = $this->event->siswa()->wherePivot('medali', 'emas')->count();
        $perak = $this->event->siswa()->wherePivot('medali', 'perak')->count();
        $perunggu = $this->event->siswa()->wherePivot('medali', 'perunggu')->count();
        $peserta = $this->event->siswa()->count();

        return [
            'emas' => $emas,
            'perak' => $perak,
            'perunggu' => $perunggu,
            'total' => $emas + $perak + $perunggu,
            'peserta' => $peserta,
        ];
    }
}
