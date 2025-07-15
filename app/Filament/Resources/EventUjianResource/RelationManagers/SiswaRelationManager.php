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
    protected static string $relationship = 'siswas'; // nama_lengkap relasi di model EventUjian
    protected static ?string $title = 'Daftar Peserta Ujian'; // Judul yang akan muncul di tab/section
    protected static ?string $recordTitleAttribute = 'nama_lengkap_lengkap'; // Atribut yang ditampilkan sebagai judul record

    public function form(Form $form): Form
    {
        // Form ini digunakan saat AttachAction dengan opsi "Create new Siswa"
        // atau jika Anda memodifikasi AttachAction untuk memasukkan data.
        // Jika Anda hanya Attach Siswa yang sudah ada, ini mungkin tidak terlalu relevan.
        return $form
            ->schema([
              Select::make('siswa_id')
                    ->relationship('siswa', 'nama_lengkap_lengkap')
                    ->required()
                    ->label('nama_lengkap Siswa'),
                // Hapus field untuk data pivot di sini
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
          ->recordTitleAttribute('nama_lengkap') // Sesuaikan jika ada nama_lengkap
        ->columns([
            Tables\Columns\TextColumn::make('index')
                ->label('NO')
                ->rowIndex(),

            Tables\Columns\TextColumn::make('nama_lengkap') // Jika kolom di siswa adalah 'nama'
                ->searchable()
                ->sortable()
                ->label('NAMA SISWA'),
            // Atau jika Anda punya 'nama_lengkap' di model Siswa:
            // Tables\Columns\TextColumn::make('nama_lengkap')
            //     ->searchable()
            //     ->sortable()
            //     ->label('NAMA LENGKAP SISWA'),

            Tables\Columns\TextColumn::make('units.nama') // Relasi ke UnitLatihan
                ->searchable()
                ->sortable()
                ->label('UNIT'),

            Tables\Columns\TextColumn::make('kelas') // Kolom 'kelas' dari model Siswa
                ->searchable()
                ->sortable()
                ->label('KELAS'),

            Tables\Columns\TextColumn::make('tempat_lahir') // Kolom 'tempat_lahir' dari model Siswa
                ->searchable()
                ->sortable()
                ->label('TEMPAT LAHIR'),

            Tables\Columns\TextColumn::make('tanggal_lahir') // Kolom 'tanggal_lahir' dari model Siswa
                ->date('d/m/Y')
                ->sortable()
                ->label('TANGGAL LAHIR'),

            Tables\Columns\TextColumn::make('current_belt_level') // Kolom 'current_belt_level' dari model Siswa
                ->searchable()
                ->sortable()
                ->label('SABUK SAAT INI (SISWA)'),

            Tables\Columns\TextColumn::make('next_belt_level') // Kolom 'next_belt_level' dari model Siswa
                ->searchable()
                ->sortable()
                ->label('SABUK BERIKUTNYA (SISWA)'),

            // Ini adalah kolom dari PIVOT TABLE, diisi saat impor
            Tables\Columns\TextColumn::make('pivot.tingkat_sabuk_saat_ini')
                ->searchable()
                ->sortable()
                ->label('TINGKAT SABUK UKT SAAT INI'),

            Tables\Columns\TextColumn::make('pivot.tingkat_sabuk_berikutnya')
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
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelect(
                        fn (Forms\Components\Select $select) => $select
                            ->placeholder('Pilih Siswa')
                            ->label('Siswa')
                            ->options(fn () => \App\Models\Siswa::pluck('nama_lengkap', 'id')) // Gunakan 'nama_lengkap' jika itu nama_lengkap kolom siswa
                            ->searchable()
                    )
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect(),
                        // Hapus field pivot di sini, karena data ini ada di model Siswa
                    ]),

                Action::make('export_siswa')
                    ->label('Ekspor Peserta')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        return Excel::download(new EventUjianSiswaExport($this->getOwnerRecord()->id), 'peserta_ujian_' . $this->getOwnerRecord()->nama_lengkap_event . '.xlsx');
                    }),

               Action::make('import_data_siswa_ujian')
                ->label('Impor dari Excel')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down') 
                ->modalHeading('Impor Data siswa UKT') 
                ->form([
                    FileUpload::make('file_excel')
                        ->label('Pilih File Excel (.xlsx, .xls, .csv)')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'])
                        ->disk('local') // Simpan file sementara di disk local
                        ->directory('temp_imports') // Direktori sementara
                        ->visibility('private'), // Pastikan file tidak dapat diakses publik
                ])
                ->action(function (array $data) {
                    try {
                        // Dapatkan path lengkap dari file yang diunggah
                        $filePath = Storage::disk('local')->path($data['file_excel']);

                        // Lakukan import
                      Excel::import(new EventUjianSiswaImport($this->getOwnerRecord()), $filePath);

                        // Hapus file setelah import selesai
                        Storage::disk('local')->delete($data['file_excel']);

                        // Tampilkan notifikasi sukses
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
                            ->persistent() // Tampilkan notifikasi hingga ditutup manual
                            ->send();

                         // Hapus file jika ada error validasi
                         if (isset($data['file_excel'])) {
                            Storage::disk('local')->delete($data['file_excel']);
                        }

                    } catch (\Exception $e) {
                        Log::error('Import Excel Error: ' . $e->getMessage());

                        Notification::make()
                            ->title('Terjadi kesalahan saat mengimpor data.')
                            ->body('Pesan Error: ' . $e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();

                         // Hapus file jika ada error lain
                         if (isset($data['file_excel'])) {
                            Storage::disk('local')->delete($data['file_excel']);
                        }
                    }
                }),
            ])
            ->actions([
                // EDIT DATA SISWA LANGSUNG (bukan pivot)
                // Hati-hati: ini mengedit data master siswa, bukan hanya untuk event ini
                Tables\Actions\EditAction::make()
                    ->label('Edit Data Siswa')
                    ->form([
                        // Pastikan nama_lengkap field sesuai dengan kolom di tabel `siswas`
                        Forms\Components\TextInput::make('current_belt_level')
                            ->label('Tingkat Sabuk Saat Ini')
                            ->default(fn (\App\Models\Siswa $record) => $record->current_belt_level),
                        Forms\Components\TextInput::make('next_belt_level')
                            ->label('Tingkat Sabuk Berikutnya')
                            ->default(fn (\App\Models\Siswa $record) => $record->next_belt_level),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->default(fn (\App\Models\Siswa $record) => $record->keterangan),
                    ])
                    // Action ini sekarang mengupdate model Siswa langsung
                    ->action(function (\App\Models\Siswa $record, array $data) {
                        $record->update([
                            'current_belt_level' => $data['current_belt_level'] ?? null,
                            'next_belt_level' => $data['next_belt_level'] ?? null,
                            'keterangan' => $data['keterangan'] ?? null,
                        ]);
                        Notification::make()->title('Data siswa berhasil diperbarui.')->success()->send();
                    }),
                DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
            
    }
}