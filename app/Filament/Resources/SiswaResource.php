<?php

namespace App\Filament\Resources;


use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use Illuminate\Support\Facades\DB;

use App\Models\Siswa;
use App\models\Unit;
use App\models\SiswaCuti;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
// use Filament\Tables\Actions\BulkActionGroup;
use Filament\Actions;
// use Filament\Tables\Actions\HeaderAction;
use Illuminate\Support\Facades\Log; // Untuk logging error
use Illuminate\Support\Facades\Storage; // Untuk menghapus file sementara
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\siswaExport;
use App\Imports\siswaImport;
use Dom\Text;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\SiswaResource\RelationManagers\CutisRelationManager;







class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;





    protected static ?string $navigationIcon = 'heroicon-o-users';
    //   protected static ?string $navigationGroup = 'Manajemen Data'; // Kelompok navigasi

    protected static ?string $label = 'Siswa';
    protected static ?string $pluralLabel = 'Siswa';

    public static function getNavigationSort(): ?int
    {
        return 1; // tampil setelah Dashboard
    }
    public static function form(Form $form): Form
{
    return $form->schema([

        /* =========================
         * INFORMASI DASAR SISWA
         * ========================= */
        Section::make('Informasi Dasar Siswa')
            ->description('Detail pribadi dan identifikasi utama siswa.')
            ->columns(3)
            ->schema([

                FileUpload::make('image')
                    ->label('Foto Siswa')
                    ->image()
                    ->imagePreviewHeight('100')
                    ->directory('siswa')
                    ->nullable()
                    ->columnSpan(1),

                Grid::make(2)
                    ->columnSpan(2)
                    ->schema([

                        TextInput::make('nis')
                            ->label('NIS')
                            ->disabled()
                            ->dehydrated(true)
                            ->helperText('NIS dibuat otomatis dan tidak dapat diubah.'),

                        TextInput::make('no_register')
                            ->label('Nomor Registrasi')
                            ->reactive()
                            ->required(fn ($get) => strtolower($get('current_belt_level') ?? '') !== 'putih')
                            ->rules(fn ($get) =>
                                strtolower($get('current_belt_level') ?? '') === 'putih'
                                    ? ['nullable', 'string', 'max:15']
                                    : ['required', 'string', 'max:255']
                            )
                            ->helperText('Boleh kosong jika sabuk Putih')
                            ->maxLength(13),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->native(false),

                        Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'AB' => 'AB',
                                'O' => 'O',
                                'Tidak Diketahui' => 'Tidak Diketahui',
                            ])
                            ->nullable()
                            ->native(false),
                    ]),
            ]),

        /* =========================
         * INFORMASI AKADEMIK
         * ========================= */
        Section::make('Informasi Akademik & Pelatihan')
            ->description('Detail unit, kelas, sabuk, dan status siswa.')
            ->columns(3)
            ->schema([

                Select::make('units_id')
                    ->label('Unit')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('current_belt_level')
                    ->label('Tingkatan Sabuk')
                    ->options(self::beltOptions())
                    ->required()
                    ->reactive(),

                TextInput::make('beladiri_yang_pernah_diikuti')
                    ->label('Beladiri yang Pernah Diikuti')
                    ->nullable(),

                DatePicker::make('joint_date')
                    ->label('Tanggal Bergabung')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->nullable(),

                Select::make('status')
                    ->label('Status Kesiswaan')
                    ->options([
                        'Aktif'       => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                        'Cuti'        => 'Cuti',
                    ])
                    ->default('Aktif')
                    ->reactive()
                    ->native(false)
                    ->columnSpan(2)
                    ->helperText(fn ($get) =>
                        $get('status') === 'Cuti'
                            ? '⚠️ Lengkapi informasi cuti di bawah.'
                            : null
                    ),
            ]),

        /* =========================
         * FORM CUTI
         * ========================= */
        Grid::make(1)
            ->columnSpanFull()
            ->visible(fn ($get) => $get('status') === 'Cuti')
            ->schema([

                DatePicker::make('cuti.tanggal_mulai')
                    ->label('Tanggal Mulai Cuti')
                    ->required()
                    ->displayFormat('d/m/Y')
                    ->native(false),

                DatePicker::make('cuti.tanggal_selesai')
                    ->label('Tanggal Selesai Cuti')
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->helperText('Boleh dikosongkan'),

                Textarea::make('cuti.alasan')
                    ->label('Alasan Cuti')
                    ->rows(3)
                    ->required(),
            ]),

        /* =========================
         * KONTAK & ALAMAT
         * ========================= */
        Section::make('Informasi Kontak & Alamat')
            ->columns(2)
            ->schema([

                TextInput::make('no_telepon')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->nullable(),

                Textarea::make('alamat_lengkap')
                    ->label('Alamat Lengkap')
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),
            ]),

        /* =========================
         * INFORMASI ORANG TUA
         * ========================= */
        Section::make('Informasi Orang Tua')
            ->columns(2)
            ->schema([

                TextInput::make('nama_ayah')->label('Nama Ayah')->nullable(),
                TextInput::make('pekerjaan_ayah')->label('Pekerjaan Ayah')->nullable(),

                TextInput::make('nama_ibu')->label('Nama Ibu')->nullable(),
                TextInput::make('pekerjaan_ibu')->label('Pekerjaan Ibu')->nullable(),
            ]),
    ]);
}


     public static function table(Table $table): Table
    {

        return $table
            ->columns([

                TextColumn::make('status_badge')
                    ->label('Status Siswa')
                    ->getStateUsing(function ($record) {
                        $createdAt = $record->created_at ?? now();
                        $beltLevel = strtolower(trim($record->current_belt_level ?? ''));
                        $days = now()->diffInDays($createdAt);

                        $requiredFields = [
                            'no_register',
                            'nama_lengkap',
                            'tanggal_lahir',
                            'tempat_lahir',
                            'no_telepon',
                            'jenis_kelamin',
                            'nama_ayah',
                            'pekerjaan_ayah',
                            'nama_ibu',
                            'pekerjaan_ibu',
                            'joint_date',
                            'alamat_lengkap',
                            'current_belt_level',
                        ];

                        $emptyFields = collect($requiredFields)->filter(function ($field) use ($record) {
                            $value = trim((string) ($record->$field ?? ''));
                            return $value === '';
                        });


                        if ($emptyFields->isNotEmpty() && $beltLevel !== 'putih') {
                            return 'Lengkapi Data';
                        }

                        // Status 2️⃣: Siswa baru (sabuk putih, no_register kosong, <30 hari)
                        if ($beltLevel === 'putih' && (empty($record->no_register) && $days <= 30)) {
                            return 'NEW';
                        }

                        // Status 3️⃣: Data lengkap
                        if ($emptyFields->isEmpty() && !empty($record->no_register)) {
                            return 'Lengkap';
                        }

                        return '-';
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Lengkapi Data' => 'danger',
                        'NEW' => 'warning',
                        'Data Lengkap' => 'success',
                        default => 'success',
                    })
                    ->tooltip(fn($state) => match ($state) {
                        'Lengkapi Data' => 'Lengkapi semua data sebelum melanjutkan.',
                        'NEW' => 'Siswa baru terdaftar kurang dari 30 hari atau belum memiliki no register.',
                        default => null,
                    })
                    ->extraAttributes(['style' => 'text-align:center; width:130px;']),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_register')
                    ->label('Nomor Registrasi')
                    ->extraAttributes(['style' => 'text-align: center'])
                    ->searchable()
                    ->sortable(),


                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->limit(30)
                    ->wrap()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->extraAttributes(['style' => 'width: 70px;'])
                    ->sortable(),

                TextColumn::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->toggleable(isToggledHiddenByDefault: true) // Sembunyikan secara default, bisa ditampilkan pengguna
                    ->searchable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date('d/m/Y') // Format tanggal
                    ->sortable(),

                TextColumn::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('unit.name')
                    ->label('Unit Latihan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('current_belt_level')
                    ->label('Sabuk')
                    ->searchable()
                    // ->extraAttributes(['style' => 'width: 100px;'])
                    ->sortable(),

                TextColumn::make('joint_date')
                    ->label('Tanggal Bergabung')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('status')
    ->label('Status')
    ->badge()
    ->state(function ($record) {
        // Jika ada cuti aktif, override jadi Cuti
        if ($record->cutiAktif) {
            return 'Cuti';
        }

        return $record->status;
    })
    ->color(fn (string $state): string => match ($state) {
        'Aktif'       => 'success',
        'Tidak Aktif' => 'danger',
        'Cuti'        => 'warning',
        default       => 'gray',
    })
    ->searchable()
    ->sortable(),

                TextColumn::make('no_telepon')
                    ->label('Nomor Telepon')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('alamat_lengkap')
                    ->label('Alamat Lengkap')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('pekerjaan_ayah')
                    ->label('Pekerjaan Ayah')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('pekerjaan_ibu')
                    ->label('Pekerjaan Ibu')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                // Kolom timestamps yang biasa ada di Filament
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i') // Format tanggal dan waktu
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara default

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara default
            ])


            ->filters([
                SelectFilter::make('current_belt_level')
                    ->label('Sabuk')
                    ->placeholder('Semua Sabuk')
                    ->options([
                        'putih'               => 'Putih',
                        'kuning'              => 'Kuning',
                        'kuning strip hijau'  => 'Kuning Strip Hijau',
                        'hijau'               => 'Hijau',
                        'hijau strip biru'    => 'Hijau Strip Biru',
                        'biru'                => 'Biru',
                        'biru strip merah'    => 'Biru Strip Merah',
                        'merah'               => 'Merah',
                        'merah strip hitam 1' => 'Merah Strip Hitam 1',
                        'merah strip hitam 2' => 'Merah Strip Hitam 2',
                        'hitam'               => 'Hitam',
                    ]),
                SelectFilter::make('units')
                    ->label('Unit Latihan')
                    ->relationship('unit', 'name') // 'unit' = nama fungsi relasi di model
                    ->placeholder('Semua Unit'),

                SelectFilter::make('status')
                    ->label('Unit Latihan')
                    ->relationship('status', 'name') // 'unit' = nama fungsi relasi di model
                    ->placeholder('Semua status'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ])
                    ->placeholder('Semua Status'),



            ])


            ->actions([
                // Ini adalah contoh aksi baris
                ActionGroup::make([
                    ViewAction::make(), // Melihat detail record
                    EditAction::make(), // Mengedit record
                    DeleteAction::make(), // Menghapus record
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkAction::make('aktifkan')
                    ->label('Aktifkan Semua')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each->update(['status' => 'Aktif']);
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
            ])
            ->headerActions([

                Tables\Actions\Action::make('Drop Data User')
                    ->label('Drop Data Siswa')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus Semua Data Siswa')
                    ->modalDescription('Anda akan menghapus SEMUA data siswa secara permanen. Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin?')
                    ->action(function (): void {
                        Siswa::query()->delete();
                        DB::statement('ALTER TABLE siswas AUTO_INCREMENT = 1');

                        Notification::make()
                            ->title('Semua data siswa telah dihapus.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('export')
                    ->label('Export Siswa')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->action(fn() => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SiswaExport, 'data_siswa_win-Hunter_' . date('m_Y') . '.xlsx')),

                Tables\Actions\Action::make('import')
                    ->label('Import Siswa')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Import Data Siswa')
                    ->modalDescription('File Excel harus sesuai format export. Data lama akan dihapus hanya jika import berhasil.')
                    ->form([
                        Forms\Components\FileUpload::make('file_excel')
                            ->label('Pilih File Excel')
                            ->required()
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                                'text/csv',
                            ])
                            ->disk('local')
                            ->directory('temp_imports'),
                    ])
                    ->action(function (array $data): void {
                        $filePath = storage_path('app/' . $data['file_excel']);
                        try {
                            // 1️⃣ Test dulu
                            Excel::import(new SiswaImport(true), $filePath);

                            // 2️⃣ Hapus data lama
                            Siswa::query()->delete();
                            DB::statement('ALTER TABLE siswas AUTO_INCREMENT = 1');

                            // 3️⃣ Import ulang (simpan data)
                            Excel::import(new SiswaImport(false), $filePath);

                            Storage::disk('local')->delete($data['file_excel']);

                            \Filament\Notifications\Notification::make()
                                ->title('Import berhasil')
                                ->body('Cek database, data siswa baru sudah masuk.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Storage::disk('local')->delete($data['file_excel']);

                            \Filament\Notifications\Notification::make()
                                ->title('Import gagal')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();

                            throw $e;
                        }
                    }),


            ]);
    }
    private static function beltOptions(): array
    {
        return [
            'putih'               => 'Putih',
            'kuning'              => 'Kuning',
            'kuning strip hijau'  => 'Kuning Strip Hijau',
            'hijau'               => 'Hijau',
            'hijau strip biru'    => 'Hijau Strip Biru',
            'biru'                => 'Biru',
            'biru strip merah'    => 'Biru Strip Merah',
            'merah'               => 'Merah',
            'merah strip hitam 1' => 'Merah Strip Hitam 1',
            'merah strip hitam 2' => 'Merah Strip Hitam 2',
            'hitam dan 1'               => 'Hitam DAN 1',
            'hitam dan 2'               => 'Hitam DAN 2',
            'hitam dan 3'               => 'Hitam DAN 3',
            'hitam dan 4'               => 'Hitam DAN 4',
            'hitam dan 5'               => 'Hitam DAN 5',
            'hitam dan 6'               => 'Hitam DAN 6',
            'hitam dan 7'               => 'Hitam DAN 7',
            'hitam dan 8'               => 'Hitam DAN 8',
            'hitam dan 9'               => 'Hitam DAN 9',
        ];
    }


    public static function getRelations(): array
    {
        return [
                CutisRelationManager::class,


        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),




        ];
    }
}
