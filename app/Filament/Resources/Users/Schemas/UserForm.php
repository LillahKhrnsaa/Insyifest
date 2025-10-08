<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Illuminate\Validation\Rules\Password;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Collection;
use app\Models\Role;

class UserForm
{
     public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // === BAGIAN 1: INFORMASI AKUN ===
                Section::make('Informasi Akun')
                    ->description('Detail login dan akses pengguna di sistem.')
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
                            ->unique('users', 'email', ignoreRecord: true)
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

                        Grid::make(2)
                            ->schema([
                                Select::make('role')
                                    ->label('Role Pengguna')
                                    ->options(Role::pluck('display_name', 'name')) // <--- pakai display_name di sini
                                    ->default(fn ($record) => $record?->roles?->first()?->name)
                                    ->afterStateHydrated(function ($set, $record) {
                                        $set('role', $record?->roles?->first()?->name);
                                    })
                                    ->afterStateUpdated(function ($state, $record) {
                                        if ($record && filled($state)) {
                                            $record->syncRoles([$state]);
                                        }
                                    })
                                    ->dehydrated(false)
                                    ->searchable()
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Pilih role')
                                    ->prefixIcon('heroicon-o-shield-check')
                                    ->optionsLimit(10),



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
                            ->directory('user-photos')
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
                    ->description('Data diri anggota Cikampek Swimming Club.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required()
                                    ->placeholder('Contoh: 081234567890')
                                    ->unique('users', 'phone', ignoreRecord: true)
                                    ->prefixIcon('heroicon-o-phone')
                                    ->maxLength(15),

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
            ]);
    }
}
