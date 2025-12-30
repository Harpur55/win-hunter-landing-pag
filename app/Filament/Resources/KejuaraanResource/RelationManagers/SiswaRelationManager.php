<?php

namespace App\Filament\Resources\KejuaraanResource\RelationManagers;

use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\Action;
use App\Exports\KejuaraanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KejuaraanSiswaImport;
use App\Exports\KejuaraanSiswaExport;
use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;  // ✅ IMPORT INI
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';
    protected static ?string $title = 'Peserta Kejuaraan';

    /**
     * Hitung kategori usia otomatis berdasarkan tanggal lahir
     */
    private function hitungKategoriUmur(?string $tanggalLahir): ?string
    {
        if (!$tanggalLahir) {
            return null;
        }

        $umur = \Carbon\Carbon::parse($tanggalLahir)->age;

        return match (true) {
            $umur >= 6 && $umur <= 11 => 'pracadet',
            $umur >= 12 && $umur <= 14 => 'cadet',
            $umur >= 15 && $umur <= 17 => 'junior',
            $umur >= 18                => 'senior',
            default                    => null,
        };
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama'),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('JK')->badge(),
                Tables\Columns\TextColumn::make('sabuk')->label('Sabuk')->badge(),
                Tables\Columns\TextColumn::make('kategori_pertandingan')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn($state) => strtolower($state) === 'kyorugi' ? 'primary' : 'info'),

                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB (kg)')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'poomsae'),

                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->label('TB (cm)')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'poomsae'),

                Tables\Columns\TextColumn::make('tageuk')->label('Taegeuk')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'kyorugi'),

                Tables\Columns\TextColumn::make('tingkat_kategori')
                    ->label('Kategori (Profesional / Reguler)')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'kyorugi'),

                Tables\Columns\TextColumn::make('kategori_atlit')->label('Kelompok Umur'),
                Tables\Columns\TextColumn::make('kelas_berat')
                    ->label('Under')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains($state, '+') => 'success',
                        default => 'primary',
                    })
                    ->weight(FontWeight::Bold),


                Tables\Columns\TextColumn::make('medali')
                    ->label('Medali')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'emas' => 'Emas',
                        'perak' => 'Perak',
                        'perunggu' => 'Perunggu',
                        default => 'Tidak Ada',
                    })
                    ->color(fn($state) => match ($state) {
                        'emas' => 'success',
                        'perak' => 'gray',
                        'perunggu' => 'warning',
                        default => 'secondary',
                    }),
            ])

            ->headerActions([
                // 🔹 Tombol Buka/Tutup Pendaftaran
                Action::make('toggle_registration')
                    ->label(fn() => $this->getOwnerRecord()->is_registration_closed ? 'Buka Pendaftaran' : 'Tutup Pendaftaran')
                    ->color(fn() => $this->getOwnerRecord()->is_registration_closed ? 'success' : 'danger')
                    ->icon(fn() => $this->getOwnerRecord()->is_registration_closed ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->requiresConfirmation()
                    ->action(function () {
                        $event = $this->getOwnerRecord();
                        $event->is_registration_closed = ! $event->is_registration_closed;
                        $event->save();

                        Notification::make()
                            ->title($event->is_registration_closed
                                ? '⛔ Pendaftaran telah ditutup.'
                                : '✅ Pendaftaran telah dibuka kembali.')
                            ->success()
                            ->send();
                    }),

                // 🔹 Import Data Peserta
                Action::make('import_data')
                    ->label('Import Data Peserta')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        FileUpload::make('file')
                            ->label('Pilih File Excel')
                            ->required()
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            ]),
                    ])
                    ->action(function (array $data) {
                        $kejuaraan = $this->getOwnerRecord();

                        if (!$kejuaraan) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Event Kejuaraan tidak ditemukan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\KejuaraanSiswaImport($kejuaraan), $data['file']);

                        Notification::make()
                            ->title('Import Berhasil')
                            ->body('Data peserta berhasil diimpor ke kejuaraan.')
                            ->success()
                            ->send();
                    }),

                // 📤 Export Data
                Action::make('export_data')
                    ->label('Export Data Peserta')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->action(function () {
                        $kejuaraan = $this->getOwnerRecord();

                        if (! $kejuaraan) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Export')
                                ->body('Data kejuaraan tidak ditemukan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // 🔹 Gunakan nama kejuaraan sebagai nama file (bersih dari karakter ilegal)
                        $namaFile = 'data_peserta_' . \Str::slug($kejuaraan->nama_kejuaraan ?? 'kejuaraan') . '_' . now()->format('Y-m-d') . '.xlsx';

                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\KejuaraanSiswaExport($kejuaraan),
                            $namaFile
                        );
                    }),

                // 🔹 Tambah Peserta
                Action::make('tambah_peserta')
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Select::make('siswa_id')
                            ->label('Nama Lengkap')
                            ->options(fn() => Siswa::all()->pluck('nama_lengkap', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $siswa = Siswa::find($state);
                                if ($siswa) {
                                    $set('nama_lengkap', $siswa->nama_lengkap);
                                    $set('tempat_lahir', $siswa->tempat_lahir);
                                    $set('tanggal_lahir', $siswa->tanggal_lahir ? Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : null);
                                    $set('jenis_kelamin', $siswa->jenis_kelamin === 'Laki-laki' ? 'L' : 'P');
                                    $set('sabuk', $siswa->current_belt_level);
                                    $set('kategori_atlit', $this->hitungKategoriUmur($siswa->tanggal_lahir));
                                }
                            }),

                        TextInput::make('nama_lengkap')->readOnly(),
                        TextInput::make('tempat_lahir')->readOnly(),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('kategori_atlit', $this->hitungKategoriUmur($state))
                            ),

                        TextInput::make('jenis_kelamin')->readOnly(),
                        TextInput::make('sabuk')->readOnly(),

                        Select::make('kategori_pertandingan')
                            ->label('Kategori Pertandingan')
                            ->options([
                                'kyorugi' => 'Kyorugi',
                                'poomsae' => 'Poomsae',
                            ])
                            ->required()
                            ->reactive(),

                        TextInput::make('berat_badan')
                            ->numeric()
                            ->suffix('kg')
                            ->required()
                            ->visible(fn($get) => $get('kategori_pertandingan') === 'kyorugi'),

                        TextInput::make('tinggi_badan')
                            ->numeric()
                            ->suffix('cm')
                            ->required()
                            ->visible(fn($get) => $get('kategori_pertandingan') === 'kyorugi'),

                        TextInput::make('kelas_berat')
                            ->label('Kelas Berat')
                            ->placeholder('U-45, U-58, U+80')
                            ->visible(fn(callable $get) => $get('kategori_pertandingan') === 'kyorugi')
                            ->dehydrated(fn(callable $get) => $get('kategori_pertandingan') === 'kyorugi')
                            ->required(fn(callable $get) => $get('kategori_pertandingan') === 'kyorugi'),



                        Select::make('tingkat_kategori')
                            ->label('Kategori (Beginer / Advance)')
                            ->options([
                                'Pro' => 'Pro',
                                'Regular' => 'Regular',
                            ]),

                        Grid::make(2)->schema([
                            Select::make('tageuk')
                                ->label('Taegeuk')
                                ->options([
                                    '1' => 'Taegeuk 1',
                                    '2' => 'Taegeuk 2',
                                    '3' => 'Taegeuk 3',
                                    '4' => 'Taegeuk 4',
                                    '5' => 'Taegeuk 5',
                                    '6' => 'Taegeuk 6',
                                    '7' => 'Taegeuk 7',
                                    '8' => 'Taegeuk 8',
                                    'koryo' => 'Koryo',
                                    'keumgang' => 'Keumgang',
                                    'taebaek' => 'Taebaek',
                                    'pyongwon' => 'Pyongwon',
                                    'sipjin' => 'Sipjin',
                                    'jitae' => 'Jitae',
                                    'cheonkwon' => 'Cheonkwon',
                                    'hangul' => 'Hangul',
                                    'ilyeo' => 'Ilyeo',
                                ])
                                ->required()
                                ->visible(fn($get) => strtolower((string)$get('kategori_pertandingan')) === 'poomsae'),

                            Select::make('tingkat_kategori')
                                ->label('Kategori (Beginer / Advance)')
                                ->options([
                                    'Beginer' => 'Beginer',
                                    'Advance' => 'Advance',
                                ])
                                ->required()
                                ->visible(fn($get) => strtolower((string)$get('kategori_pertandingan')) === 'poomsae'),
                        ]),

                        Select::make('kategori_atlit')
                            ->label('Kelompok Usia')
                            ->options([
                                'pracadet' => 'Pra-Cadet',
                                'cadet'    => 'Cadet',
                                'junior'   => 'Junior',
                                'senior'   => 'Senior',
                            ])
                            ->required()
                            ->default(fn(callable $get) => $this->hitungKategoriUmur($get('tanggal_lahir')))
                            ->reactive(),

                        Select::make('medali')
                            ->label('Medali')
                            ->options([
                                'tidak_ada' => 'Tidak Ada',
                                'emas'      => 'Emas',
                                'perak'     => 'Perak',
                                'perunggu'  => 'Perunggu',
                            ])
                            ->default('tidak_ada'),
                    ])
                    ->action(function (array $data) {
                        $event = $this->getOwnerRecord();

                        $sudahAda = $event->siswa()
                            ->where('kejuaraan_siswa.siswa_id', $data['siswa_id'])
                            ->where('kejuaraan_siswa.kategori_pertandingan', $data['kategori_pertandingan'])
                            ->exists();

                        if ($sudahAda) {
                            Notification::make()
                                ->title('⚠️ Siswa sudah terdaftar di kategori ini.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $event->siswa()->attach($data['siswa_id'], $data);

                        Notification::make()
                            ->title('✅ Peserta berhasil ditambahkan.')
                            ->success()
                            ->send();
                    }),
            ])

            ->actions([

                Tables\Actions\Action::make('hapus_peserta')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $kejuaraan = $this->getOwnerRecord();

                        // Hapus relasi di tabel pivot kejuaraan_siswa
                        $kejuaraan->siswa()->detach($record->id);

                        Notification::make()
                            ->title('Peserta Dihapus 🗑️')
                            ->body('Siswa telah dihapus dari daftar peserta kejuaraan.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('berat_badan')
                            ->numeric()
                            ->suffix('kg')
                            ->visible(fn($record) => strtolower($record->pivot->kategori_pertandingan) === 'kyorugi'),

                        TextInput::make('tinggi_badan')
                            ->numeric()
                            ->suffix('cm')
                            ->visible(fn($record) => strtolower($record->pivot->kategori_pertandingan) === 'kyorugi'),

                        TextInput::make('kelas_berat')   // ✅ harus 'kelas_berat'
                            ->label('Kelas Berat')
                            ->visible(fn($record) => strtolower($record->pivot->kategori_pertandingan) === 'kyorugi')
                            ->placeholder('U-45, U-58, U+80'),

                        Select::make('tingkat_kategori')
                            ->label('Kategori (Beginer / Advance)')
                            ->options([
                                'Pro' => 'Pro',
                                'Regular' => 'Regular',
                            ]),

                        Select::make('tageuk')
                            ->label('Taegeuk')
                            ->options([
                                '1' => 'Taegeuk 1',
                                '2' => 'Taegeuk 2',
                                '3' => 'Taegeuk 3',
                                '4' => 'Taegeuk 4',
                                '5' => 'Taegeuk 5',
                                '6' => 'Taegeuk 6',
                                '7' => 'Taegeuk 7',
                                '8' => 'Taegeuk 8',
                                'koryo' => 'Koryo',
                                'keumgang' => 'Keumgang',
                                'taebaek' => 'Taebaek',
                                'pyongwon' => 'Pyongwon',
                                'sipjin' => 'Sipjin',
                                'jitae' => 'Jitae',
                                'cheonkwon' => 'Cheonkwon',
                                'hangul' => 'Hangul',
                                'ilyeo' => 'Ilyeo',
                            ])
                            ->visible(fn($record) => strtolower($record->pivot->kategori_pertandingan) === 'poomsae'),

                        Select::make('tingkat_kategori')
                            ->label('Kategori (Beginer / Advance)')
                            ->options([
                                'pro' => 'Pro',
                                'reguler' => 'Reguler',
                            ])
                            ->visible(fn($record) => strtolower($record->pivot->kategori_pertandingan) === 'poomsae'),

                        Select::make('kategori_atlit')
                            ->label('Kelompok Usia')
                            ->options([
                                'pracadet' => 'Pra-Cadet',
                                'cadet'    => 'Cadet',
                                'junior'   => 'Junior',
                                'senior'   => 'Senior',
                            ]),

                        Select::make('medali')
                            ->label('Medali')
                            ->options([
                                'tidak_ada' => 'Tidak Ada',
                                'emas'      => 'Emas',
                                'perak'     => 'Perak',
                                'perunggu'  => 'Perunggu',
                            ]),
                    ])
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        $record->pivot->update($data);
                        return $data;
                    })
                    ->fillForm(fn($record) => $record->pivot->toArray()),
            ]);
    }
}
