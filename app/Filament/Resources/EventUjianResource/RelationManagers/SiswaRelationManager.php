<?php 

namespace App\Imports;
namespace App\Filament\Resources\EventUjianResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\FileUpload;

use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventUjianExport;
use App\Imports\EventUjianSiswaImport;
 // Ekspor untuk peserta ujian



// Import Model jika belum otomatis terimport
use App\Models\Siswa;
use App\Models\EventUjian;
use Illuminate\Database\Eloquent\Builder;


class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa'; // nama_lengkap relasi di model EventUjian
    protected static ?string $title = 'Daftar Peserta Ujian'; // Judul yang akan muncul di tab/section
    protected static ?string $recordTitleAttribute = 'nama_lengkap_lengkap'; // Atribut yang ditampilkan sebagai judul record

     public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Field-field untuk AttachAction jika Anda ingin mengizinkan pengisian data pivot
                // secara manual saat melampirkan siswa yang sudah ada.
                // Jika tidak, biarkan kosong atau hapus bagian form() di AttachAction
                Forms\Components\TextInput::make('pivot.current_belt_level')
                    ->label('Tingkat Sabuk Saat Ini (Pivot)')
                    ->nullable(),
                Forms\Components\TextInput::make('pivot.next_belt_level')
                    ->label('Tingkat Sabuk Berikutnya (Pivot)')
                    ->nullable(),
                Forms\Components\TextInput::make('pivot.keterangan')
                    ->label('Keterangan (Pivot)')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_lengkap') // Menggunakan nama_lengkap dari model Siswa
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(), // Ini akan menghasilkan nomor urut baris

                Tables\Columns\TextColumn::make('nama_lengkap') // Kolom 'nama_lengkap' langsung dari model Siswa
                    ->searchable()
                    ->sortable()
                    ->label('NAMA SISWA'),

                Tables\Columns\TextColumn::make('unit.nama_unit') // Relasi Siswa ke Unit, kolom 'nama_unit'
                    ->searchable()
                    ->sortable()
                    ->label('UNIT'),

                Tables\Columns\TextColumn::make('kelas.nama_kelas') // Relasi Siswa ke Kelas, kolom 'nama_kelas'
                    ->searchable()
                    ->sortable()
                    ->label('KELAS'),

                Tables\Columns\TextColumn::make('tempat_lahir') // Kolom langsung dari model Siswa
                    ->searchable()
                    ->sortable()
                    ->label('TEMPAT LAHIR'),

                Tables\Columns\TextColumn::make('tanggal_lahir') // Kolom langsung dari model Siswa
                    ->date('d/m/Y') // Format tanggal
                    ->sortable()
                    ->label('TANGGAL LAHIR'),

                Tables\Columns\TextColumn::make('current_belt_level') // Kolom langsung dari model Siswa
                    ->searchable()
                    ->sortable()
                    ->label('SABUK SAAT INI (MASTER)'), // Label lebih jelas

                Tables\Columns\TextColumn::make('next_belt_level') // Kolom langsung dari model Siswa
                    ->searchable()
                    ->sortable()
                    ->label('SABUK BERIKUTNYA (MASTER)'), // Label lebih jelas

                // Ini adalah kolom dari PIVOT TABLE (dari event_ujian_siswa)
                Tables\Columns\TextColumn::make('pivot.current_belt_level')
                    ->searchable()
                    ->sortable()
                    ->label('TINGKAT SABUK UKT SAAT INI'),

                Tables\Columns\TextColumn::make('pivot.next_belt_level')
                    ->searchable()
                    ->sortable()
                    ->label('TINGKAT SABUK UKT BERIKUTNYA'),

                Tables\Columns\TextColumn::make('pivot.keterangan')
                    ->searchable()
                    ->label('KET UKT'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // ✅ Attach Siswa yang Sudah Ada (dengan data pivot)
                AttachAction::make()
                    ->label('Pilih Siswa yang Ada')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('current_belt_level')
                            ->label('Tingkat Sabuk UKT Saat Ini')
                            ->default(fn (Forms\Get $get) => Siswa::find($get('recordId'))?->current_belt_level) // Ambil default dari Siswa
                            ->required(),
                        Forms\Components\TextInput::make('next_belt_level')
                            ->label('Tingkat Sabuk UKT Berikutnya')
                            ->default(fn (Forms\Get $get) => Siswa::find($get('recordId'))?->next_belt_level) // Ambil default dari Siswa
                            ->required(),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->nullable(),
                    ]),

                // ✅ Tambah Data Siswa Manual (Custom Action)
                Action::make('tambah_siswa_manual')
                    ->label('Tambah Data Siswa Ujian')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->form([
                        Select::make('siswa_id')
                            ->label('Pilih Siswa')
                            ->options(Siswa::pluck('nama_lengkap', 'id')) // Hanya nama_lengkap jika tidak ada relasi `units` di pluck
                            ->searchable()
                            ->required()
                            ->reactive() // Membuat form interaktif
                            ->afterStateUpdated(function ($state, callable $set) {
                                $siswa = Siswa::with(['unit', 'kelas'])->find($state); // Load relasi unit dan kelas

                                if ($siswa) {
                                    $set('unit', $siswa->unit?->nama_unit ?? '-'); // Sesuaikan nama kolom unit
                                    $set('kelas', $siswa->kelas?->nama_kelas ?? '-'); // Sesuaikan nama kolom kelas
                                    $set('tempat_lahir', $siswa->tempat_lahir);
                                    $set('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d'));
                                    $set('tingkat_sabuk_saat_ini_master', $siswa->current_belt_level); // Data master
                                    $set('tingkat_sabuk_berikutnya_master', $siswa->next_belt_level); // Data master
                                    // Default untuk pivot
                                    $set('tingkat_sabuk_saat_ini_pivot', $siswa->current_belt_level);
                                    $set('tingkat_sabuk_berikutnya_pivot', $siswa->next_belt_level);
                                }
                            }),

                        TextInput::make('unit')
                            ->label('Unit')
                            ->disabled(),

                        TextInput::make('kelas')
                            ->label('Kelas')
                            ->disabled(),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->disabled(),

                        TextInput::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->disabled(),

                        // Tampilkan data master siswa sebagai informasi (disabled)
                        TextInput::make('tingkat_sabuk_saat_ini_master')
                            ->label('Tingkat Sabuk Siswa (Master Data)')
                            ->disabled(),

                        TextInput::make('tingkat_sabuk_berikutnya_master')
                            ->label('Tingkat Sabuk Siswa (Master Data)')
                            ->disabled(),

                        // Field untuk data yang akan disimpan di pivot table
                        Forms\Components\TextInput::make('tingkat_sabuk_saat_ini_pivot')
                            ->label('Tingkat Sabuk UKT Saat Ini')
                            ->required(),

                        Forms\Components\TextInput::make('tingkat_sabuk_berikutnya_pivot')
                            ->label('Tingkat Sabuk UKT Berikutnya')
                            ->required(),

                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        $eventUjian = $this->getOwnerRecord();

                        if ($eventUjian->siswas()->where('siswa_id', $data['siswa_id'])->exists()) { // Menggunakan 'siswas'
                            Notification::make()
                                ->title('Siswa sudah terdaftar dalam ujian ini.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $eventUjian->siswas()->attach($data['siswa_id'], [ // Menggunakan 'siswas'
                            'current_belt_level' => $data['tingkat_sabuk_saat_ini_pivot'], // Simpan ke 'current_belt_level' di pivot
                            'next_belt_level' => $data['tingkat_sabuk_berikutnya_pivot'],   // Simpan ke 'next_belt_level' di pivot
                            'keterangan' => $data['keterangan'],
                        ]);

                        Notification::make()
                            ->title('Siswa berhasil ditambahkan ke ujian.')
                            ->success()
                            ->send();
                    }),

                // ✅ Ekspor Peserta (Action)
                Action::make('export_siswa')
                    ->label('Ekspor Peserta')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Pastikan EventUjianSiswaExport memiliki konstruktor yang menerima ID event ujian
                        return Excel::download(new EventUjianSiswaExport($this->getOwnerRecord()->id), 'peserta_ujian_' . $this->getOwnerRecord()->nama_ujian . '.xlsx'); // Sesuaikan nama_lengkap_event menjadi nama_ujian jika itu nama kolomnya
                    }),

                // ✅ Impor dari Excel (Action)
                Action::make('import_data_siswa_ujian')
                    ->label('Impor dari Excel')
                    ->color('info')
                    ->icon('heroicon-o-document-arrow-down')
                    ->modalHeading('Impor Data Siswa UKT')
                    ->form([
                        FileUpload::make('file_excel')
                            ->label('Pilih File Excel (.xlsx, .xls, .csv)')
                            ->required()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'])
                            ->storeFiles(false) // Jangan simpan file di storage, langsung proses di memory
                            ->preserveFilenames(), // Agar nama file tetap sama
                    ])
                    ->action(function (array $data) {
                        try {
                            $file = $data['file_excel']; // Ambil objek file dari FileUpload

                            // Pastikan EventUjianSiswaImport menggunakan EventUjian object di constructor
                            Excel::import(new EventUjianSiswaImport($this->getOwnerRecord()), $file);

                            Notification::make()
                                ->title('Berhasil mengimpor data!')
                                ->success()
                                ->send();

                        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                            $failures = $e->failures();
                            $errorMessages = [];
                            foreach ($failures as $failure) {
                                $errorMessages[] = "Baris " . ($failure->row()) . ": " . implode(", ", $failure->errors());
                            }
                            Log::error('Import Excel Validation Error: ' . implode('; ', $errorMessages));

                            Notification::make()
                                ->title('Gagal mengimpor data! Ada kesalahan validasi.')
                                ->body(implode('<br>', $errorMessages))
                                ->danger()
                                ->persistent()
                                ->send();

                        } catch (\Exception $e) {
                            Log::error('Import Excel Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

                            Notification::make()
                                ->title('Terjadi kesalahan saat mengimpor data.')
                                ->body('Pesan Error: ' . $e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                // ✅ Edit Data Pivot (bukan data master siswa)
                Tables\Actions\EditAction::make()
                    ->label('Edit Data Ujian Siswa') // Label lebih spesifik
                    ->modalHeading(fn (Siswa $record) => 'Edit Data Ujian untuk ' . $record->nama_lengkap)
                    ->form([
                        // Pastikan field ini sesuai dengan kolom di pivot table
                        Forms\Components\TextInput::make('pivot.current_belt_level')
                            ->label('Tingkat Sabuk UKT Saat Ini')
                            ->required(), // Jika harus diisi
                        Forms\Components\TextInput::make('pivot.next_belt_level')
                            ->label('Tingkat Sabuk UKT Berikutnya')
                            ->required(), // Jika harus diisi
                        Forms\Components\TextInput::make('pivot.keterangan')
                            ->label('Keterangan')
                            ->nullable(),
                    ])
                    ->action(function (Siswa $record, array $data) {
                        $this->getOwnerRecord()->siswas()->updateExistingPivot($record->id, [
                            'current_belt_level' => $data['pivot']['current_belt_level'] ?? null, // Ambil dari data form
                            'next_belt_level' => $data['pivot']['next_belt_level'] ?? null,     // Ambil dari data form
                            'keterangan' => $data['pivot']['keterangan'] ?? null,
                        ]);
                        Notification::make()->title('Data ujian siswa berhasil diperbarui.')->success()->send();
                    }),
                DetachAction::make(), // Untuk melepaskan hubungan siswa dari event ujian
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}