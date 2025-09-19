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
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\Action;
use App\Exports\KejuaraanExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;



class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';
    protected static ?string $title = 'Peserta Kejuaraan';



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
                    ->hidden(fn($record): bool => $record->kategori_pertandingan === 'poomsae'),

                // Kolom Tinggi Badan (TB) akan disembunyikan jika kategori_pertandingan adalah 'Poomsae'
                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->label('TB (cm)')
                    ->hidden(fn($record): bool => $record->kategori_pertandingan === 'poomsae'),
                Tables\Columns\TextColumn::make('tageuk')->label('Taegeuk'),
                Tables\Columns\TextColumn::make('kategori_atlit')->label('Kelompok Umur'),
                Tables\Columns\TextColumn::make('berat_badan')->label('BB (kg)'),
                Tables\Columns\TextColumn::make('tinggi_badan')->label('TB (cm)'),




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
                        'emas' => 'success',     // hijau
                        'perak' => 'gray',       // abu
                        'perunggu' => 'warning', // kuning / oranye
                        default => 'secondary',  // default abu
                    }),



            ])
            ->headerActions([
                Action::make('export_data')
                    ->label('Export Data')
                    ->color('primary') // tombol biru
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $event = $this->getOwnerRecord();

                        // download langsung file excel
                        return Excel::download(new KejuaraanExport($event), 'data_kejuaraan.xlsx');
                    }),

                Tables\Actions\Action::make('tambah_peserta')
                    ->label('Tambah Peserta')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        // ✅ Pilih siswa (pakai relationship)
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
                                }
                            }),

                        // Snapshot biodata siswa
                        TextInput::make('nama_lengkap')->readOnly(),
                        TextInput::make('tempat_lahir')->readOnly(),
                        DatePicker::make('tanggal_lahir')->readOnly(),
                        TextInput::make('jenis_kelamin')->readOnly(),
                        TextInput::make('sabuk')->readOnly(),

                        // Input tambahan
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

                        // Jika kategori = poomsae → tampil Taegeuk 1–8
                        Select::make('tageuk')
                            ->label('Taegeuk')
                            ->options([
                                'Beginer' => 'Beginer',
                                'Advance' => 'Advance',
                               
                            ])
                            ->required()
                            ->visible(fn($get) => $get('kategori_pertandingan') === 'poomsae'),


                        Select::make('kategori_atlit')
                            ->label('Kelompok Usia')
                            ->options([
                                'pracadet' => 'Pra-Cadet',
                                'cadet'    => 'Cadet',
                                'junior'   => 'Junior',
                                'senior'   => 'Senior',
                            ])
                            ->required(),

                        // TextInput::make('berat_badan')->numeric()->suffix('kg')->required(),
                        // TextInput::make('tinggi_badan')->numeric()->suffix('cm')->required(),

                        Select::make('medali')
                            ->label('Medali')
                            ->options([
                                'tidak_ada' => 'Tidak Ada',
                                'emas'      => 'Emas',
                                'perak'     => 'Perak',
                                'perunggu'  => 'Perunggu',
                            ])
                            // ->colors([
                            //     'tidak_ada' => 'gray',
                            //     'emas'      => 'success',
                            //     'perak'     => 'secondary',
                            //     'perunggu'  => 'warning',
                            // ])
                            ->default('tidak_ada'),
                    ])
                    ->action(function (array $data) {
                        $event = $this->getOwnerRecord();

                        // Cegah duplikasi siswa
                        if ($event->siswa()->where('siswa_id', $data['siswa_id'])->exists()) {
                            Notification::make()->title('⚠️ Siswa sudah terdaftar.')->danger()->send();
                            return;
                        }

                        // Simpan ke pivot
                        $event->siswa()->attach($data['siswa_id'], $data);

                        Notification::make()->title('✅ Peserta berhasil ditambahkan.')->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        TextInput::make('pivot.berat_badan')
                            ->numeric()
                            ->suffix('kg')
                            ->required()
                            ->visible(fn($record) => $record->pivot->kategori_pertandingan === 'kyorugi'),

                        TextInput::make('pivot.tinggi_badan')
                            ->numeric()
                            ->suffix('cm')
                            ->required()
                            ->visible(fn($record) => $record->pivot->kategori_pertandingan === 'kyorugi'),

                        Select::make('pivot.tageuk')
                            ->label('Taegeuk')
                            ->options([
                                'Beginer' => 'Beginer',
                                'Advance' => 'Advance',
                                
                            ])
                            ->required()
                            ->visible(fn($record) => strtolower((string)($record?->pivot?->kategori_pertandingan ?? '')) === 'poomsae'),

                        Select::make('pivot.kategori_atlit')
                            ->label('Kelompok Usia')
                            ->options([
                                'pracadet' => 'Pra-Cadet',
                                'cadet'    => 'Cadet',
                                'junior'   => 'Junior',
                                'senior'   => 'Senior',
                            ])
                            ->required(),

                        Select::make('pivot.medali')
                            ->options([
                                'tidak_ada' => 'Tidak Ada',
                                'emas'      => 'Emas',
                                'perak'     => 'Perak',
                                'perunggu'  => 'Perunggu',
                            ]),
                    ]),
                // Tables\Actions\DetachAction::make(),
            ]);
    }
}
