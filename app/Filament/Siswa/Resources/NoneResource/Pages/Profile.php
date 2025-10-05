<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $title = 'Profil Saya';
    protected static string $view = 'filament.siswa.pages.profile';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if ($siswa) {
            $this->form->fill($siswa->toArray());
        } else {
            $this->form->fill([
                'nama_lengkap' => $user->name,
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                // 🔹 Identitas Dasar
                Forms\Components\Section::make('Identitas Dasar')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Profil')
                            ->image()
                            ->imageEditor()
                            ->directory('siswa')
                            ->previewable(true)
                            ->columnSpan(1)
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('nama_lengkap')
                                ->label('Nama Lengkap')
                                ->required()
                                ->disabled(fn() => !$this->isEditing),

                            Forms\Components\TextInput::make('nis')
                                ->label('NIS')
                                ->disabled()
                                ->dehydrated(false),
                        ])->columnSpan(1),
                    ]),

                // 🔹 Biodata
                Forms\Components\Section::make('Biodata')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'])
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                // 🔹 Kontak
                Forms\Components\Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpan(2)
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                // 🔹 Orang Tua
                Forms\Components\Section::make('Informasi Orang Tua')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                // 🔹 Akademik & Unit
                Forms\Components\Section::make('Akademik & Unit Latihan')
                    ->columns(1)
                    ->schema([
                        Forms\Components\TextInput::make('no_register')
                            ->label('No. Register')
                            ->disabled(fn() => !$this->isEditing),

                        Select::make('current_belt_level')
                            ->label('Tingkatan Sabuk')
                            ->options(self::beltOptions())
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('units_id')
                            ->label('Unit Latihan')
                            ->options(Unit::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(Kelas::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->disabled(fn() => !$this->isEditing),
                    ]),

                // 🔹 Lain-lain
                Forms\Components\Section::make('Lain-lain')
                    ->schema([
                        Forms\Components\TextInput::make('beladiri_yang_pernah_diikuti')
                            ->label('Beladiri yang Pernah Diikuti')
                            ->disabled(fn() => !$this->isEditing),
                    ]),
            ])
            ->statePath('data');
    }

    public function edit(): void
    {
        $this->isEditing = true;

        Notification::make()
            ->title('Mode Edit')
            ->body('Sekarang kamu bisa mengubah data profil.')
            ->info()
            ->send();
    }

    public function save(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Simpan atau buat siswa
        $siswa = Siswa::updateOrCreate(
            ['user_id' => $user->id],
            $this->form->getState()
        );

        // Sinkron nama user dengan nama_lengkap siswa
        if (!empty($siswa->nama_lengkap)) {
            $user->fill(['name' => $siswa->nama_lengkap])->save();
        }

        $this->isEditing = false;

        Notification::make()
            ->title('Berhasil')
            ->success()
            ->body('Profil berhasil diperbarui.')
            ->send();
    }

    // 🔹 Tambah action tombol Edit & Simpan
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('edit')
                ->label('Edit Profil')
                ->icon('heroicon-o-pencil')
                ->action('edit')
                ->hidden(fn() => $this->isEditing),

            \Filament\Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('save')
                ->hidden(fn() => !$this->isEditing),
        ];
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
}
