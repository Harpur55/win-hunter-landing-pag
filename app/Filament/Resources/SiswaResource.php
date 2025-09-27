<?php

namespace App\Filament\Resources;


use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use Illuminate\Support\Facades\DB;

use App\Models\Siswa;
use App\models\Unit;
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
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;







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
        return $form
            ->schema([
                Section::make('Informasi Dasar Siswa')
                    ->description('Detail pribadi dan identifikasi utama siswa.')
                    ->columns(3) // Menggunakan 3 kolom untuk tata letak yang lebih fleksibel
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto Siswa')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->directory('siswa')

                            ->nullable()
                            ->columnSpan(1),
                        // Menempati 1 kolom dari 3 di dalam Section ini

                        Grid::make(2) // Grid terpisah di dalam Section untuk detail teks
                            ->columnSpan(2) // Menempati 2 kolom sisanya di dalam Section ini
                            ->schema([
                                TextInput::make('nis')
                                    ->label('NIS')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->helperText('NIS akan otomatis dibuat atau tidak dapat diubah.'),

                                TextInput::make('no_register')
                                    ->label('Nomor Registrasi')
                                    ->reactive()
                                    ->required(fn(callable $get) => strtolower($get('current_belt_level') ?? '') !== 'putih') // hanya wajib jika bukan putih
                                    ->rules(function (callable $get) {
                                        $sabuk = strtolower($get('current_belt_level') ?? '');
                                        if ($sabuk === 'putih') {
                                            return ['nullable', 'string', 'max:255'];
                                        }
                                        return ['required', 'string', 'max:255'];
                                    })
                                    ->helperText('Boleh kosong jika sabuk Putih, wajib diisi untuk sabuk di atas Putih'),

                                // Pastikan unik, abaikan record saat mengedit yang sudah ada

                                TextInput::make('nama_lengkap') // Sesuaikan dengan nama field di database
                                    ->label('Nama Lengkap')
                                    ->required(), // Wajib diisi

                                Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->required() // Wajib diisi
                                    ->native(false), // Untuk tampilan yang lebih modern

                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->placeholder('Contoh: Jakarta'), // Contoh placeholder

                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->native(false) // Untuk tampilan yang lebih modern
                                    ->displayFormat('d/m/Y'), // Format tampilan tanggal

                                Select::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->options([
                                        'A' => 'A',
                                        'B' => 'B',
                                        'AB' => 'AB',
                                        'O' => 'O',
                                        'Tidak Diketahui' => 'Tidak Diketahui', // Opsi jika tidak tahu
                                    ])
                                    ->nullable() // Boleh kosong
                                    ->native(false),

                            ]),

                    ]),
                Section::make('Informasi Akademik & Pelatihan')
                    ->description('Detail mengenai unit latihan, kelas, sabuk, dan status siswa.')
                    ->columns(3) // Menggunakan 3 kolom
                    ->schema([
                        Select::make('units_id')
                            ->label('Unit')
                            ->relationship('unit', 'name') // 'unit' = nama fungsi relasi di model
                            ->searchable()
                            ->preload()
                            ->required(),



                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'name') // kolom 'nama' ditampilkan
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('current_belt_level')
                            ->label('Tingkatan Sabuk')
                            ->options(self::beltOptions())
                            ->required()
                            ->reactive(), // Wajib diisi

                        TextInput::make('beladiri_yang_pernah_diikuti')
                            ->label('Beladiri yang Pernah Diikuti')
                            ->nullable()
                            ->placeholder('Contoh: Pencak Silat, Taekwondo, dll'),

                        DatePicker::make('joint_date')
                            ->label('Tanggal Bergabung')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->nullable(), // Boleh kosong

                        Select::make('status')
                            ->label('Status Kesiswaan')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Tidak Aktif' => 'Tidak Aktif',
                                'Cuti' => 'Cuti',
                            ])
                            ->required()
                            ->default('Aktif') // Default nilai 'Aktif'
                            ->native(false)
                            ->columnSpan(2), // Memakan 2 kolom untuk status
                    ]),

                Section::make('Informasi Kontak & Alamat')
                    ->description('Detail kontak dan alamat lengkap siswa.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('no_telepon')
                            ->label('Nomor Telepon')
                            ->tel() // Tipe input telepon
                            ->nullable() // Boleh kosong
                            ->placeholder('Contoh: 081234567890'),

                        Textarea::make('alamat_lengkap') // Menggunakan Textarea untuk alamat
                            ->label('Alamat Lengkap')
                            ->rows(3) // Tinggi textarea
                            ->nullable() // Boleh kosong
                            ->columnSpanFull(), // Mengambil lebar penuh di grid ini
                    ]),

                Section::make('Informasi Orang Tua')
                    ->description('Detail informasi ayah dan ibu siswa.')
                    ->columns(2) // Menggunakan 2 kolom
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->nullable(),

                        TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah')
                            ->nullable(),

                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->nullable(),

                        TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->nullable(),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([


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
                    ->badge() // Menampilkan sebagai badge dengan warna otomatis Filament
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Tidak Aktif' => 'danger',
                        'Cuti' => 'warning',
                        default => 'gray',
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


                Tables\Actions\Action::make('export')
                    ->label('Export Siswa')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->action(fn() => \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SiswaExport, 'data_siswa.xlsx')),

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
            'hitam'               => 'Hitam',
        ];
    }


    public static function getRelations(): array
    {
        return [
            // Add relation managers here, for example:
            // RelationManagers\SomeRelationManager::class,
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
