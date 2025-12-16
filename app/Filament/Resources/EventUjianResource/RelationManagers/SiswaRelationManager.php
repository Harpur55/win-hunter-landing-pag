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
                Tables\Columns\TextColumn::make('index')
                    ->label('NO')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_register')
                    ->label('No Register')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir'),

                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('current_belt_level')
                    ->label('Sabuk Master (Terkini)')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

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
                    ->color('warning')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pivot.keterangan')
                    ->label('Status Ujian')
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

                // Toggle Pendaftaran
                Action::make('toggle_registration')
                    ->label(function () {
                        $event = $this->getOwnerRecord();
                        return $event->is_registration_closed
                            ? 'Buka Pendaftaran'
                            : 'Tutup Pendaftaran';
                    })
                    ->color(fn() => $this->getOwnerRecord()->is_registration_closed ? 'success' : 'danger')
                    ->icon(fn() => $this->getOwnerRecord()->is_registration_closed
                        ? 'heroicon-o-lock-open'
                        : 'heroicon-o-lock-closed')
                    ->requiresConfirmation()
                    ->action(function () {
                        $event = $this->getOwnerRecord();
                        $event->is_registration_closed = ! $event->is_registration_closed;
                        $event->save();

                        Notification::make()
                            ->title(
                                $event->is_registration_closed
                                    ? '⛔ Pendaftaran telah ditutup.'
                                    : '✅ Pendaftaran telah dibuka kembali.'
                            )
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
                            ->afterStateUpdated(function ($state, $set) {
                                $siswa = Siswa::with(['unit', 'kelas'])->find($state);

                                if ($siswa) {
                                    $set('no_register', $siswa->no_register);
                                    $set('no_register_disabled', !empty($siswa->no_register));
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
                                ->disabled(fn($get) => $get('no_register_disabled'))
                                ->required(fn($get) => !$get('no_register_disabled'))
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
                                ->afterStateHydrated(function ($set, $get) {
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
                    ->action(function ($data) {
                        $eventUjian = $this->getOwnerRecord();

                        if ($eventUjian->siswa()->where('siswa_id', $data['siswa_id'])->exists()) {
                            Notification::make()
                                ->title('Siswa sudah terdaftar.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $siswa = Siswa::find($data['siswa_id']);

                        if ($siswa && empty($siswa->no_register) && !empty($data['no_register'])) {
                            $siswa->update([
                                'no_register' => $data['no_register'],
                            ]);
                        }

                        $eventUjian->siswa()->attach($data['siswa_id'], [
                            'current_belt_level' => $data['current_belt_level'],
                            'next_belt_level'    => $data['next_belt_level'],
                            'keterangan'         => $data['keterangan'],
                        ]);

                        Notification::make()
                            ->title('Siswa berhasil ditambahkan ke ujian.')
                            ->success()
                            ->send();
                    }),

                // Jalankan Cron Update Sabuk
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
                            Artisan::call('belt:update --instant');
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

                // Import Excel
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
                    ->action(function ($data) {
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

                // Export Data
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
                    ->action(function (Siswa $record, $data) {
                        $this->getOwnerRecord()
                            ->siswa()
                            ->updateExistingPivot($record->id, [
                                'next_belt_level' => $data['pivot']['next_belt_level'] ?? null,
                                'keterangan'      => $data['pivot']['keterangan'],
                            ]);
                    }),

            Action::make('update_hasil')
    ->label('Edit Hasil Ujian')
    ->color('warning')
    ->modalHeading('Update Hasil Ujian Siswa')
    ->modalDescription('Atur hasil ujian untuk siswa ini.')
    ->form([
        Select::make('keterangan')
            ->label('Hasil Ujian')
            ->options([
                'on_proses'   => 'On Proses',
                'lulus'       => 'Lulus',
                'tidak_lulus' => 'Tidak Lulus',
            ])
            ->required(),
    ])
    ->action(function (Siswa $record, array $data) {

        $event = $this->getOwnerRecord(); // event ujian

        // Pastikan event ada
        if (! $event) {
            throw new \Exception('Event ujian tidak ditemukan.');
        }

        // Ambil data pivot yang sedang diedit
        $pivot = $event->siswa()->where('siswa_id', $record->id)->first()?->pivot;

        if (! $pivot) {
            throw new \Exception('Data ujian siswa tidak ditemukan di pivot.');
        }

        /*
         |---------------------------------------------------------
         | Jika TIDAK LULUS → sabuk siswa kembali ke sabuk sebelumnya
         | (diambil dari kolom current_belt_level di pivot)
         |---------------------------------------------------------
         */
        if ($data['keterangan'] === 'tidak_lulus') {
            $record->update([
                'sabuk' => $pivot->current_belt_level,
            ]);
        }

        // Update kolom di pivot
        $event->siswa()
            ->updateExistingPivot($record->id, [
                'keterangan' => $data['keterangan'],
            ]);

        Notification::make()
            ->title('Hasil ujian siswa berhasil diperbarui.')
            ->success()
            ->send();
    }),

                Tables\Actions\DetachAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])

->actionsPosition(ActionsPosition::AfterColumns)

            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                    ->label('Hapus Terpilih'),
            ]);
    }


    // ===============================
    // SABUK HELPER
    // ===============================

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
        if (!$current) return null;

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

        $index = array_search(strtolower(trim($current)), $belts);

        if ($index !== false && isset($belts[$index + 1])) {
            return ucfirst($belts[$index + 1]);
        }

        return null;
    }
}
