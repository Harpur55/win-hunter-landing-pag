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
use Filament\Forms\Components\Tabs\Tab;
use Maatwebsite\Excel\Facades\Excel;

class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';
    protected static ?string $title = 'Peserta Kejuaraan';

    /**
     * Helper untuk hitung kategori umur dari tanggal lahir
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
                Tables\Columns\TextColumn::make('kategori_pertandingan')->label('Kategori'),

                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('BB (kg)')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'poomsae'),

                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->label('TB (cm)')
                    ->hidden(fn($record): bool => strtolower((string)($record?->kategori_pertandingan ?? '')) === 'poomsae'),

                Tables\Columns\TextColumn::make('tageuk')->label('Taegeuk'),
                Tables\Columns\TextColumn::make('tingkat_kategori')->label('Kategori (Beginer / Advance)'),
                Tables\Columns\TextColumn::make('kategori_atlit')->label('Kelompok Umur'),

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
                Action::make('export_data')
                    ->label('Export Data')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $event = $this->getOwnerRecord();
                        return Excel::download(new KejuaraanExport($event), 'data_kejuaraan.xlsx');
                    }),

                Tables\Actions\Action::make('tambah_peserta')
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Select::make('siswa_id')
                            ->label('Nama Lengkap')
                            ->options(fn() => \App\Models\Siswa::all()->pluck('nama_lengkap', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $siswa = \App\Models\Siswa::find($state);
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
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('kategori_atlit', $this->hitungKategoriUmur($state));
                            }),

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

                        Grid::make(2) // grid 2 kolom
                            ->schema([

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
                                    ])
                                    ->required()
                                    ->visible(fn($get) => strtolower((string) $get('kategori_pertandingan')) === 'poomsae'),

                                Select::make('tingkat_kategori')
                                    ->label('Kategori (Beginer / Advance)')
                                    ->options([
                                        'Beginer' => 'Beginer',
                                        'Advance' => 'Advance',
                                    ])
                                    ->required()
                                    ->visible(fn($get) => strtolower((string) $get('kategori_pertandingan')) === 'poomsae'),

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

                        if ($event->siswa()->where('siswa_id', $data['siswa_id'])->exists()) {
                            Notification::make()->title('⚠️ Siswa sudah terdaftar.')->danger()->send();
                            return;
                        }

                        $event->siswa()->attach($data['siswa_id'], $data);

                        Notification::make()->title('✅ Peserta berhasil ditambahkan.')->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('berat_badan')
                            ->numeric()
                            ->suffix('kg')
                            ->required()
                            ->visible(fn($record) => $record->pivot->kategori_pertandingan === 'kyorugi'),

                        TextInput::make('tinggi_badan')
                            ->numeric()
                            ->suffix('cm')
                            ->required()
                            ->visible(fn($record) => $record->pivot->kategori_pertandingan === 'kyorugi'),

                        Select::make('tageuk')
                            ->label('Taegeuk')
                            ->options([
                                'Beginer' => 'Beginer',
                                'Advance' => 'Advance',
                            ])
                            ->required()
                            ->visible(fn($record) => strtolower((string)($record?->pivot?->kategori_pertandingan ?? '')) === 'poomsae'),

                        Select::make('kategori_atlit')
                            ->label('Kelompok Usia')
                            ->options([
                                'pracadet' => 'Pra-Cadet',
                                'cadet'    => 'Cadet',
                                'junior'   => 'Junior',
                                'senior'   => 'Senior',
                            ])
                            ->required(),

                        Select::make('medali')
                            ->options([
                                'tidak_ada' => 'Tidak Ada',
                                'emas'      => 'Emas',
                                'perak'     => 'Perak',
                                'perunggu'  => 'Perunggu',
                            ]),
                    ])
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        // simpan ke pivot
                        $record->pivot->update($data);
                        return $data;
                    })
                    ->fillForm(function ($record) {
                        // isi default dari pivot
                        return $record->pivot->toArray();
                    }),

            ]);
    }
}
