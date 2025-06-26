<?php

namespace App\Exports;

use App\Models\Siswa; // Pastikan ini mengarah ke model Siswa Anda
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
use Carbon\Carbon; // Pastikan ini diimpor jika Anda menggunakan Carbon

class SiswaExport implements FromCollection, WithMapping, WithStyles, ShouldAutoSize, WithColumnWidths, WithStartRow, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::all(); // Ambil semua data siswa
    }

    /**
     * Menentukan baris header kolom data.
     * Baris ini akan dicetak di baris yang ditentukan oleh `startRow()`.
     * @return array
     */
    public function headings(): array
    {
        // Ini adalah judul kolom yang akan menjadi header di Excel.
        // Urutan ini harus sama persis dengan urutan data yang Anda kembalikan di metode `map()`.
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
            'FOTO SISWA', // Ini akan menampilkan path file, bukan gambar itu sendiri
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
     * Memetakan data dari setiap objek Siswa ke dalam array yang akan ditulis ke Excel.
     * Urutan elemen dalam array ini harus sama persis dengan `headings()`.
     * @param Siswa $siswa
     * @return array
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->no_register,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->unit_latihan,
             $siswa->kelas,
            $siswa->sabuk,
            $siswa->tempat_lahir, 
            $siswa->tanggal_lahir ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : null,
            $siswa->golongan_darah,
            $siswa->image, // Ini akan mencetak path file, bukan gambar itu sendiri
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
     * Menentukan lebar kolom spesifik di Excel.
     * @return array
     */
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
            'J' => 10, // KELAS
            'K' => 15, // SABUK
            'L' => 40, // ALAMAT LENGKAP
            'M' => 20, // NOMOR TELEPON
            'N' => 25, // NAMA AYAH
            'O' => 25, // PEKERJAAN AYAH
            'P' => 25, // NAMA IBU
            'Q' => 25, // PEKERJAAN IBU
            'R' => 15, // STATUS
            'S' => 20, // TANGGAL BERGABUNG
        ];
    }

    /**
     * Menentukan baris di mana data (termasuk header dari `headings()`) akan dimulai.
     * Karena Anda memiliki 3 baris judul dokumen di atas, header akan dimulai di baris ke-4.
     * @return int
     */
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
        $sheet->setCellValue('A1', 'FORMULIR PENDAFTARAN SISWA WIN-HUNTER');
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