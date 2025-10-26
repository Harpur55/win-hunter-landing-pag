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
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;


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

                    Tables\Columns\TextColumn::make('geup_display')
    ->label('Geup / Dan')
    ->getStateUsing(function ($record) {
        $currentBelt = strtolower($record->pivot->current_belt_level ?? '');
        return self::beltToGeup($currentBelt);
    })
    ->badge()
    ->color('gray'),
           

              
                Tables\Columns\TextColumn::make('pivot.next_belt_level')
                    ->label('Sabuk Berikutnya')
                    ->badge()
                    ->color('warning') // optional: warna kuning
                    ->sortable(),

              

                Tables\Columns\TextColumn::make('pivot.keterangan')
                    ->label('Hasil Ujian')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'lulus'       => 'success',
                        'tidak_lulus' => 'danger',
                        'on_proses'   => 'warning',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'lulus'       => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                        'on_proses'   => 'On Proses',
                        default       => ucfirst($state),
                    }),
            ])
            ->headerActions([

                //buatkan tombol untuk tutuppendaftaran
              Action::make('toggle_registration')
        ->label(function () {
            $event = $this->getOwnerRecord(); // Ambil kejuaraan induk
            return $event->is_registration_closed ? 'Buka Pendaftaran' : 'Tutup Pendaftaran';
        })
        ->color(function () {
            $event = $this->getOwnerRecord();
            return $event->is_registration_closed ? 'success' : 'danger';
        })
        ->icon(function () {
            $event = $this->getOwnerRecord();
            return $event->is_registration_closed ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed';
        })
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

    // Tambah Peserta Manual
    Action::make('tambah_siswa_manual')
        ->label('Tambah Peserta')
        ->icon('heroicon-o-plus-circle')
        ->color('primary')
        ->button()
        ->extraAttributes(['class' => 'px-3 py-1 text-sm rounded-md'])
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

                        // kontrol input no_register
                        if (!empty($siswa->no_register)) {
                            $set('no_register_disabled', true);
                        } else {
                            $set('no_register_disabled', false);
                        }

                        $set('unit', $siswa->unit?->name ?? '-');
                        $set('kelas', $siswa->kelas?->name ?? '-');
                        $set('tempat_lahir', $siswa->tempat_lahir);
                        $set('tanggal_lahir', optional($siswa->tanggal_lahir)->format('Y-m-d'));
                        $set('current_belt_level', $siswa->current_belt_level);
                    }
                }),

            Forms\Components\Hidden::make('no_register_disabled')->default(false),

            Forms\Components\Grid::make(2)->schema([
                TextInput::make('no_register')
                    ->label('No Register')
                    ->disabled(fn(callable $get) => $get('no_register_disabled'))
                    ->required(fn(callable $get) => !$get('no_register_disabled'))
                    ->helperText('Jika kosong di master, isi manual di sini.'),

                TextInput::make('unit')->label('Unit')->readOnly(),
                TextInput::make('kelas')->label('Kelas')->readOnly(),
                TextInput::make('tempat_lahir')->label('Tempat Lahir')->disabled(),
                TextInput::make('tanggal_lahir')->label('Tanggal Lahir')->disabled(),
                TextInput::make('current_belt_level')->label('Sabuk Saat Ini')->readOnly(),
            ]),

            Forms\Components\Grid::make(2)->schema([
               Select::make('next_belt_level')
    ->label('Sabuk Berikutnya (Pivot)')
    ->options(self::beltOptions())
    ->required()
    ->reactive()
    ->afterStateHydrated(function (callable $set, callable $get) {
        // Jika current_belt_level sudah ada, isi otomatis sabuk berikutnya
        $current = strtolower($get('current_belt_level') ?? '');
        $set('next_belt_level', self::getNextBelt($current));
    }),
                
             
                Select::make('keterangan')
                    ->label('Status Ujian')
                    ->options([
                        'on_proses'   => 'On Proses',
                        'lulus'       => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                    ])
                    ->default('on_proses')
                    ->required(),
            ]),
        ])
        ->action(function (array $data) {
            $eventUjian = $this->getOwnerRecord();

            if ($eventUjian->siswa()->where('siswa_id', $data['siswa_id'])->exists()) {
                Notification::make()->title('Siswa sudah terdaftar.')->danger()->send();
                return;
            }

            // update no_register ke tabel siswa jika masih kosong
            $siswa = Siswa::find($data['siswa_id']);
            if ($siswa && empty($siswa->no_register) && !empty($data['no_register'])) {
                $siswa->update([
                    'no_register' => $data['no_register'],
                ]);
            }

            // simpan ke pivot ujian
            $eventUjian->siswa()->attach($data['siswa_id'], [
                'current_belt_level' => $data['current_belt_level'], // dari master
                'next_belt_level'    => $data['next_belt_level'],   // input manual
                           // default
                'keterangan'         => $data['keterangan'],        // default
            ]);

            Notification::make()->title('Siswa berhasil ditambahkan ke ujian.')->success()->send();
        }),

    // Jalankan Update Sabuk
    Action::make('updateBelt')
        ->label('Update Sabuk')
        ->icon('heroicon-o-bolt')
        ->color('success')
        ->button()
        ->extraAttributes(['class' => 'px-3 py-1 text-sm rounded-md'])
        ->requiresConfirmation()
        ->modalHeading('Jalankan Cron Job Update Sabuk')
        ->modalDescription('Ini akan memproses semua siswa yang lulus dan memperbarui sabuk mereka.')
        ->modalSubmitActionLabel('Jalankan Sekarang')
        ->action(function () {
            try {
                Artisan::call('belt:update --instant'); // mode instan
                $output = Artisan::output();

                Notification::make()
                    ->title('Proses Berhasil')
                    ->success()
                    ->body("Cron job `belt:update --instant` telah dijalankan.<br><br><pre>{$output}</pre>")
                    ->send();
            } catch (\Throwable $th) {
                Notification::make()
                    ->title('Terjadi Kesalahan')
                    ->danger()
                    ->body($th->getMessage())
                    ->send();
            }
        }),

    // Impor Excel
    Action::make('import_data_siswa_ujian')
        ->label('Impor Excel')
        ->icon('heroicon-o-document-arrow-down')
        ->color('info')
        ->button()
        ->extraAttributes(['class' => 'px-3 py-1 text-sm rounded-md'])
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

   
    Excel::import(
        new EventUjianSiswaImport($eventUjian),
        $filePath
    );

   
    Notification::make()
        ->title('✅ Data siswa ujian berhasil diimport')
        ->success()
        ->send();

        }),

    // Export Peserta
    Action::make('export_siswa')
        ->label('Export')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('success')
        ->button()
        ->extraAttributes(['class' => 'px-3 py-1 text-sm rounded-md'])
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
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Edit Data Ujian Siswa')
                    ->form([
                        TextInput::make('pivot.current_belt_level')
                            ->label('Sabuk UKT Saat Ini')
                            ->disabled()
                            ->dehydrated(false),


                        Select::make('pivot.keterangan')
                            ->label('Status Ujian')
                            ->options([
                                'on_proses'   => 'On Proses',
                                'lulus'       => 'Lulus',
                                'tidak_lulus' => 'Tidak Lulus',
                            ])
                            ->required(),
                    ])
                    ->action(function (Siswa $record, array $data) {
                        $this->getOwnerRecord()
                            ->siswa()
                            ->updateExistingPivot($record->id, [
                                'next_belt_level' => $data['pivot']['next_belt_level'],
                                'keterangan'      => $data['pivot']['keterangan'],
                            ]);
                    }),

                Tables\Actions\DetachAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns) // 👈 tombol pindah ke kiri, biar selalu kelihatan
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Hapus Terpilih'),
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
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam'              => 'Hitam',
        ];
    }

   private static function beltToGeup(?string $belt): string
{
    $mapping = [
        'putih'               => '10 Geup',
        'kuning'              => '9 Geup',
        'kuning strip hijau'  => '8 Geup',
        'hijau'               => '7 Geup',
        'hijau strip biru'    => '6 Geup',
        'biru'                => '5 Geup',
        'biru strip merah'    => '4 Geup',
        'merah'               => '3 Geup',
        'merah strip hitam 1' => '2 Geup',
        'merah strip hitam 2' => '1 Geup',
        'hitam'               => '1 Dan',
    ];

    return $mapping[$belt] ?? '-';
}

public static function getNextBelt(?string $current): ?string
{
    if (!$current) {
        return null;
    }

    // Daftar urutan sabuk dari rendah ke tinggi
    $belts = [
        'putih',
        'kuning',
        'kuning strip hijau',
        'hijau',
        'hijau strip biru',
        'biru',
        'biru strip merah',
        'merah',
        'merah strip hitam satu',
        'merah strip hitam dua',
        'hitam',
    ];

    // Cari posisi sabuk saat ini
    $index = array_search(strtolower(trim($current)), $belts);

    // Jika ditemukan dan belum sabuk terakhir, ambil sabuk berikutnya
    if ($index !== false && isset($belts[$index + 1])) {
        return ucfirst($belts[$index + 1]);
    }

    // Jika sabuk sudah terakhir atau tidak ditemukan, tetap null
    return null;
}



    
}


