<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachResource\Pages;
use App\Filament\Resources\CoachResource\RelationManagers;

use App\Models\Coach;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn; 

use Filament\Actions;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\HeaderAction; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoachExport;

use Filament\Forms\Components\Textarea;


class CoachResource extends Resource
{
    protected static ?string $model = Coach::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    //   protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Pelatih';

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
            Section::make('Informasi Dasar Profil')
                ->description('Detail pribadi dan peran dalam organisasi.')
                ->columns(3) // Menggunakan 3 kolom untuk tata letak yang fleksibel
                ->schema([
                    // Kolom untuk Foto (memakan 1 kolom)
                    FileUpload::make('foto') // Nama field 'foto'
                        ->label('Foto Profil')
                        ->image()
                        ->imagePreviewHeight('200')
                        ->directory('profil_photos') 
                        ->avatar() 
                        ->nullable() 
                        ->columnSpan(1), 

                    // Grid untuk detail teks (memakan 2 kolom sisa dari Section)
                    Grid::make(2) 
                        ->columnSpan(2) 
                        ->schema([
                            TextInput::make('nama') // Nama field 'nama'
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Masukkan nama lengkap'),

                            Select::make('role') 
                                ->label('Peran')
                                ->options([
                                    'Pelatih' => 'Pelatih',
                                    'Asisten Pelatih' => 'Asisten Pelatih',
                                    'Pengurus' => 'Pengurus',
                                    'Administrator' => 'Administrator',
                                ])
                                ->required()
                                ->native(false) 
                                ->placeholder('Pilih peran'),

                            TextInput::make('sabuk') 
                                ->label('Tingkatan Sabuk')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Contoh: Hitam Dan I'),
                            
                            Select::make('status') // Nama field 'status'
                                ->label('Status')
                                ->options([
                                    'Aktif' => 'Aktif',
                                    'Tidak Aktif' => 'Tidak Aktif',
                                    'Cuti' => 'Cuti',
                                ])
                                ->required()
                                ->default('Aktif') // Nilai default 'Aktif'
                                ->native(false),
                        ]),
                ]),

            Section::make('Detail Kontak')
                ->description('Informasi kontak dan alamat.')
                ->columns(1) 
                ->schema([
                    TextInput::make('nomor_telepon') 
                        ->label('Nomor Telepon')
                        ->tel() 
                        ->nullable() 
                        ->maxLength(20)
                        ->placeholder('Contoh: 081234567890'),

                    Textarea::make('alamat') 
                        ->label('Alamat Lengkap')
                        ->rows(3) 
                        ->nullable() 
                        ->placeholder('Masukkan alamat lengkap Anda'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('foto')
                    ->label('Foto Profil')
                    ->formatStateUsing(fn ($state) => $state ? '<img src="' . asset($state) . '" alt="Foto Profil" class="w-16 h-16 rounded-full">' : 'Tidak ada foto')
                    ->html(),
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sabuk')
                    ->label('Tingkatan Sabuk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),       
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                    
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([

                Action::make('export_coach')
                    ->icon('heroicon-o-document-arrow-up')
                    ->label('Export Data')
                    ->color('success')
                    ->action(fn() =>Excel::download(new CoachExport, 'data_pelatih.xlsx'))
                    ->requiresConfirmation()
                    ->modalHeading('Ekspor Data Pelatih')
                    // ->modalSubheading('Apakah Anda yakin ingin mengekspor data pelatih?')
                    // ->modalButton('Ekspor')
                    ->action(function () {
                        return Excel::download(new CoachExport, 'data_pelatih.xlsx');
                        // Logika untuk ekspor data
                        try {
                            return Excel::download(new CoachExport, 'data_pelatih.xlsx');
                        } catch (\Exception $e) {
                            Log::error('Error exporting data: ' . $e->getMessage());
                            throw new \Exception('Gagal mengekspor data. Silakan coba lagi.');
                        }
                    }),

                 Action::make('import_pelatih')
                ->label('Impor dari Excel')
                ->color('info')
                ->icon('heroicon-o-document-arrow-down') 
                ->modalHeading('Import Data Pelatih') 
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
                        // Excel::import(new siswaImport, $filePath);

                        // // Hapus file setelah import selesai
                        // Storage::disk('local')->delete($data['file_excel']);

                        // // Tampilkan notifikasi sukses
                        // Notification::make()
                        //     ->title('Berhasil mengimpor data!')
                        //     ->success()
                        //     ->send();

                    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                        $failures = $e->failures();
                        $errorMessages = [];
                        foreach ($failures as $failure) {
                            $errorMessages[] = "Baris " . ($failure->row()) . ": " . implode(", ", $failure->errors());
                        }
                        Log::error('Import Excel Validation Error: ' . implode('; ', $errorMessages));

                        // Notification::make()
                        //     ->title('Gagal mengimpor data! Ada kesalahan validasi.')
                        //     ->body(implode('<br>', $errorMessages))
                        //     ->danger()
                        //     ->persistent() // Tampilkan notifikasi hingga ditutup manual
                        //     ->send();

                         // Hapus file jika ada error validasi
                         if (isset($data['file_excel'])) {
                            Storage::disk('local')->delete($data['file_excel']);
                        }

                    } catch (\Exception $e) {
                        Log::error('Import Excel Error: ' . $e->getMessage());

                        // Notification::make()
                        //     ->title('Terjadi kesalahan saat mengimpor data.')
                        //     ->body('Pesan Error: ' . $e->getMessage())
                        //     ->danger()
                        //     ->persistent()
                        //     ->send();

                         // Hapus file jika ada error lain
                         if (isset($data['file_excel'])) {
                            Storage::disk('local')->delete($data['file_excel']);
                        }
                    }
                }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoaches::route('/'),
            'create' => Pages\CreateCoach::route('/create'),
            'edit' => Pages\EditCoach::route('/{record}/edit'),
        ];
    }
}
