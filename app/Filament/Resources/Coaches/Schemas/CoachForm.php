<?php

namespace App\Filament\Resources\Coaches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;     
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Model;

class CoachForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // === BAGIAN 1: INFORMASI AKUN ===
                Section::make('Informasi Akun')
                    ->description('Detail login dan akses coach di sistem.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->placeholder('Contoh: John Doe')
                            ->maxLength(255)
                            ->autofocus()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                if (empty($get('email')) && $state) {
                                    $baseEmail = Str::lower(str_replace(' ', '', $state));
                                    $set('email', $baseEmail . '@cikampekswimmingclub.gmail.com');
                                }
                            })
                            ->extraAttributes(['class' => 'focus:ring-2 focus:ring-200'])
                            ->columnSpanFull(),

                        TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->readonly()
                            ->placeholder('contoh@cikampekswimmingclub.gmail.com')
                            ->prefixIcon('heroicon-o-envelope')
                            ->helperText('Otomatis terisi berdasarkan nama.')
                            ->extraAttributes(['class' => 'focus:border-primary-500'])
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->minLength(8)
                                    ->revealable()
                                    ->confirmed()
                                    ->live(onBlur: true),

                                TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->password()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(false)
                                    ->visible(fn (string $operation, Get $get) => $operation === 'create' || filled($get('password'))),
                            ]),

                        // Role otomatis jadi coach (hidden)
                        Hidden::make('role')
                            ->default('coach'),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('active')
                                    ->label('Akun Aktif')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->required()
                                    ->default(true)
                                    ->columnSpan(1),

                                Hidden::make('email_verified_at')
                                    ->default(now()),
                            ])
                            ->columnSpanFull(),

                        FileUpload::make('photo_path')
                            ->label('Foto Profil')
                            ->image()
                            ->disk('public')
                            ->directory('coach-photos')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                            ])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->maxSize(5120) // 5MB
                            ->previewable(true)
                            ->downloadable(true)
                            ->openable(true)
                            ->columnSpanFull(),

                    ])
                    ->collapsible()
                    ->compact()
                    ->columnSpanFull(),

                // === BAGIAN 2: INFORMASI PRIBADI ===
                Section::make('Informasi Pribadi')
                    ->description('Data diri coach.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->placeholder('Contoh: 081234567890')
                                    ->prefixIcon('heroicon-o-phone')
                                    ->maxLength(15)
                                    ->helperText('Masukkan nomor telepon unik yang belum pernah terdaftar.'),

                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'MALE' => 'Laki-laki',
                                        'FEMALE' => 'Perempuan',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-user'),

                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->maxDate(now())
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->placeholder('Pilih tanggal')
                                    ->prefixIcon('heroicon-o-cake'),
                            ]),
                    ])
                    ->collapsible()
                    ->compact()
                    ->columnSpanFull(),

                // === BAGIAN 3: INFORMASI COACH ===
                Section::make('Informasi Coach')
                    ->description('Detail keahlian dan pengalaman coach.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Textarea::make('bio')
                            ->label('Biografi')
                            ->placeholder('Ceritakan tentang pengalaman dan keahlian coach...')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->compact()
                    ->columnSpanFull(),
            ]);
    }
}
