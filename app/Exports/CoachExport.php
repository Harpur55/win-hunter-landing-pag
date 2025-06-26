<?php

namespace App\Exports;

use App\Models\coach;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStartRow; // Tambahkan ini
use Maatwebsite\Excel\Concerns\WithStrictNullComparison; // Tambahkan ini (opsional, tapi disarankan)

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;



class CoachExport implements FromCollection , WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths, WithStartRow, WithStrictNullComparison
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
      public function columnWidths(): array
    {
        // Sesuaikan lebar ini agar sesuai dengan konten Anda.
        // Pastikan jumlah entri ini sesuai dengan jumlah heading Anda.
        return [
            'A' => 12, // NIS
            'B' => 18, // NOMOR REGISTRASI
            'C' => 30, // NAMA LENGKAP
            'D' => 18, // JENIS KELAMIN
            'E' => 20, // TEMPAT LAHIR
            'F' => 18, // TANGGAL LAHIR
            'G' => 18, // GOLONGAN DARAH
            'H' => 30, // FOTO SISWA (path)
            'I' => 20, // UNIT LATIHAN
         
        ];
    }
      public function startRow(): int
    {
        return 5; 
    }

    /**
     * Menerapkan styling ke worksheet Excel.
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->fromArray($this->headings(), null, 'A4');

        // Hitung jumlah kolom dari headings yang sebenarnya
        $numColumns = count($this->headings());
        // Konversi nomor kolom menjadi huruf kolom (misal: 1 -> A, 2 -> B, dst.)
        $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($numColumns);

        // --- Bagian Header Dokumen (Baris 1, 2, 3) ---
        // Judul Utama Dokumen (Baris 1)
        $sheet->setCellValue('A1', 'FORMULIR PENDAFTARAN PELATIH WIN-HUNTER');
        // Gabungkan sel dari A1 hingga kolom terakhir di baris 1
        $sheet->mergeCells('A1:' . $lastColumnLetter . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // "PERIODE : APRIL" (Baris 2)
        $sheet->setCellValue('A2', 'PERIODE : ' . strtoupper(Carbon::now()->format('F')));
        $sheet->mergeCells('A2:' . $lastColumnLetter . '2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // "TAHUN : 2025" (Baris 3)
        $sheet->setCellValue('A3', 'TAHUN : ' . Carbon::now()->format('Y'));
        $sheet->mergeCells('A3:' . $lastColumnLetter . '3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // --- Styling  Kolom Data (Baris 4) ---
        // Header data dicetak di baris yang ditentukan oleh `startRow()`
       $headerRow = 4;
$headerRange = 'A' . $headerRow . ':' . $lastColumnLetter . $headerRow;

$sheet->getStyle($headerRange)->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FF000000'],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFCCCCCC'], // abu-abu terang
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
        

        // --- Styling untuk Data Cells (Mulai dari Baris 5) ---
        // Data dimulai setelah baris header data
        $dataStartRow = $headerRow + 1; // 4 + 1 = 5
        $lastDataRow = $sheet->getHighestRow(); // Dapatkan baris terakhir yang berisi data
        $dataRange = 'A' . $dataStartRow . ':' . $lastColumnLetter . $lastDataRow;

        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Warna border hitam
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Styling khusus untuk kolom pertama (NIS) agar teks rata tengah
        $sheet->getStyle('A' . $dataStartRow . ':' . 'A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Menambahkan gambar logo (jika diperlukan) - PERHATIAN: ini butuh lebih banyak konfigurasi
        // Ini contoh saja, Anda mungkin perlu menyesuaikan lokasi dan ukuran
        // Pastikan Anda telah mengimpor PhpOffice\PhpSpreadsheet\Drawing
        // if (file_exists(public_path('images/logo.png'))) {
        //     $drawing = new \PhpOffice\PhpSpreadsheet\Drawing();
        //     $drawing->setName('Logo');
        //     $drawing->setDescription('Logo Win Hunter');
        //     $drawing->setPath(public_path('images/logo.png')); // Sesuaikan path logo Anda
        //     $drawing->setHeight(70); // Sesuaikan tinggi logo
        //     $drawing->setCoordinates('Q1'); // Tempatkan di sel Q1
        //     $drawing->setWorksheet($sheet);
        // }

        return $sheet;
    }

}
