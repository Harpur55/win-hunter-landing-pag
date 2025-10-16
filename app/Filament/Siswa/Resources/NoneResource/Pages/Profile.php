<?php

namespace App\Filament\Siswa\Pages;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Unit;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;

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

        // ✅ Pastikan $data selalu berupa array
        $this->data = $siswa
            ? $siswa->toArray()
            : ['nama_lengkap' => $user->name];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Dasar')
                    ->columns(1)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto Profil')
                            ->image()
                            ->avatar()
                            ->directory('profil_photos')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(1024)
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->imageEditor()
                            ->previewable(true)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->disabled(fn() => !$this->isEditing),

                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->disabled()
                            ->dehydrated(false),
                    ]),

                Forms\Components\Section::make('Biodata')
                    ->columns(2)
                    ->schema([
                        Select::make('jenis_kelamin')
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

                        Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->options(['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'])
                            ->disabled(fn() => !$this->isEditing),
                    ]),

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
                            ->columnSpanFull()
                            ->disabled(fn() => !$this->isEditing),
                    ]),

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

                Forms\Components\Section::make('Akademik & Unit Latihan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_register')
                            ->label('Nomor Register')
                            ->helperText('Nomor register akan tertera pada sertifikat ujian.')
                            ->placeholder('Contoh: REG-2025-001')
                            ->maxLength(50)
                            ->disabled(fn() => !$this->isEditing)
                            ->required(fn() => $this->isEditing),

                        Select::make('current_belt_level')
                            ->label('Tingkatan Sabuk')
                            ->options(self::beltOptions())
                            ->disabled()
                            ->required(),

                        Select::make('units_id')
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
                            ->disabled()
                            ->dehydrated(false),
                    ]),

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
            ->title('Mode Edit Aktif')
            ->body('Sekarang kamu dapat mengubah data profil.')
            ->info()
            ->send();
    }

    public function save(): void
    {
        $user = Auth::user();
        $data = $this->form->getState();

        $siswa = Siswa::updateOrCreate(['user_id' => $user->id], $data);

        if (!empty($siswa->nama_lengkap)) {
            $user->name = $siswa->nama_lengkap;
            $user->save();
        }

        $this->isEditing = false;

        Notification::make()
            ->title('Profil Berhasil Disimpan')
            ->success()
            ->body('Profil telah diperbarui. Nomor register digunakan untuk sertifikat ujian.')
            ->send();
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
