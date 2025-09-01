<?php

namespace App\Filament\Resources\EventUjianResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use App\Models\Siswa;
use App\Imports\EventUjianSiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventUjianSiswaExport;


class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';
    protected static ?string $title = 'Daftar Peserta Ujian';

    public function table(Table $table): Table
    {
       return $table
            ->recordTitleAttribute('nama_lengkap')
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('NO')->rowIndex(),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama Siswa')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('no_register')->label('No Register')->sortable(),
                Tables\Columns\TextColumn::make('unit.name')->label('Unit')->sortable(),
                Tables\Columns\TextColumn::make('kelas.name')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                Tables\Columns\TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->date('d/m/Y'),

                // ✅ Master belt (status terkini)
                Tables\Columns\TextColumn::make('current_belt_level')
                    ->label('Sabuk Master (Terkini)')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                // ✅ Data pivot (histori ujian)
                Tables\Columns\TextColumn::make('pivot.current_belt_level')
                    ->label('Sabuk Saat Pendaftaran UKT')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('pivot.next_belt_level')
                    ->label('Target Sabuk UKT')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('pivot.keterangan')
                    ->label('Hasil Ujian')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'lulus'       => 'success',
                        'tidak_lulus' => 'danger',
                        'on_proses'   => 'warning',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'lulus'       => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                        'on_proses'   => 'On Proses',
                        default       => ucfirst($state),
                    }),
            ])
            ->headerActions([
                // Tambah Peserta Manual
                Action::make('tambah_siswa_manual')
                    ->label('Tambah Peserta Ujian')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->form([
                         Select::make('siswa_id')
                ->label('Pilih Siswa')
                ->options(Siswa::pluck('nama_lengkap', 'id'))
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $siswa = Siswa::with(['unit', 'kelas'])->find($state);
                    if ($siswa) {
                        $set('no_register', $siswa->no_register);
                        $set('unit', $siswa->unit?->name ?? '-');
                        $set('kelas', $siswa->kelas?->name ?? '-');
                        $set('tempat_lahir', $siswa->tempat_lahir);
                        $set('tanggal_lahir', optional($siswa->tanggal_lahir)->format('Y-m-d'));
                        $set('current_belt_level', $siswa->current_belt_level);
                    }
                }),

            // Info otomatis dari master
            Forms\Components\Grid::make(2)->schema([
                TextInput::make('no_register')
                    ->label('No Register')
                    ->reactive()
                    ->rules(function (callable $get) {
                        $sabuk = strtolower($get('current_belt_level') ?? '');
                        if ($sabuk === 'putih') {
                            return ['nullable', 'string', 'max:255'];
                        }
                        return ['required', 'string', 'max:255'];
                    })
                    ->helperText('Wajib diisi untuk sabuk di atas Putih, boleh kosong jika sabuk Putih'),

                TextInput::make('unit')->label('Unit')->readOnly(),
                TextInput::make('kelas')->label('Kelas')->readOnly(),
                TextInput::make('tempat_lahir')->label('Tempat Lahir')->disabled(),
                TextInput::make('tanggal_lahir')->label('Tanggal Lahir')->disabled(),
                TextInput::make('current_belt_level')->label('Sabuk Saat Ini')->readOnly(),
            ]),

            // Input ujian (pivot)
                     Forms\Components\Grid::make(2)->schema([
                     Select::make('next_belt_level')
                    ->label('Sabuk Berikutnya (Pivot)')
                    ->options(self::beltOptions())
                    ->required(),

                            Select::make('keterangan')
                                ->label('Status Ujian')
                                ->options([
                                    'on_proses'   => 'On Proses',
                                    'lulus'       => 'Lulus',
                                    'tidak_lulus' => 'Tidak Lulus',
                                ])
                                ->default('on_proses') // default
                                ->required(),
                        ]),
                    ])
                    ->action(function (array $data) {
                        $eventUjian = $this->getOwnerRecord();

                        if ($eventUjian->siswa()->where('siswa_id', $data['siswa_id'])->exists()) {
                            Notification::make()->title('Siswa sudah terdaftar.')->danger()->send();
                            return;
                        }

                        $eventUjian->siswa()->attach($data['siswa_id'], [
                            'current_belt_level' => $data['current_belt_level'], // dari master
                            'next_belt_level'    => $data['next_belt_level'],   // input manual
                            'keterangan'         => $data['keterangan'],        // default = on_proses
                        ]);

                        Notification::make()->title('Siswa berhasil ditambahkan ke ujian.')->success()->send();
                    }),

                // Impor Excel
             Action::make('import_data_siswa_ujian')
                ->label('Impor dari Excel')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down')
                ->form([
                    FileUpload::make('file_excel')
                        ->label('File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $eventUjian = $this->getOwnerRecord();
                    $filePath = storage_path('app/' . $data['file_excel']);
                
                    \Maatwebsite\Excel\Facades\Excel::import(
                        new \App\Imports\EventUjianSiswaImport($eventUjian),
                        $filePath
                    );
                
                    \Filament\Notifications\Notification::make()
                        ->title('✅ Data siswa ujian berhasil diimport')
                        ->success()
                        ->send();
                }),

              Action::make('export_siswa')
        ->label('Export Peserta Ujian')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->action(function () {
            $eventUjian = $this->getOwnerRecord();

            return Excel::download(
                new EventUjianSiswaExport($eventUjian),
                'peserta_ujian_' . $eventUjian->id . '.xlsx'
            );
        }),
    
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Data Ujian Siswa')
                    ->form([
                        TextInput::make('pivot.current_belt_level')->label('Sabuk UKT Saat Ini')->required(),
                        TextInput::make('pivot.next_belt_level')->label('Sabuk UKT Berikutnya')->required(),
                        Select::make('pivot.keterangan')
                            ->label('Status Ujian')
                            ->options([
                                'on_proses'   => 'On Proses',
                                'lulus'       => 'Lulus',
                                'tidak_lulus' => 'Tidak Lulus',
                            ])
                            ->required(),
                    ])
                    ->action(fn (Siswa $record, array $data) =>
                        $this->getOwnerRecord()->siswa()->updateExistingPivot($record->id, $data['pivot'])
                    ),
                Tables\Actions\DetachAction::make(),
            ]);
    }

    private static function beltOptions(): array
    {
        return [
            'putih'              => 'Putih',
            'kuning'             => 'Kuning',
            'kuning strip hijau' => 'Kuning Strip Hijau',
            'hijau'              => 'Hijau',
            'hijau strip biru'   => 'Hijau Strip Biru',
            'biru'               => 'Biru',
            'biru strip merah'   => 'Biru Strip Merah',
            'merah'              => 'Merah',
            'merah strip hitam 1'=> 'Merah Strip Hitam 1',
            'merah strip hitam 2'=> 'Merah Strip Hitam 2',
            'hitam'              => 'Hitam',
        ];
    }
}

