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
use Illuminate\Support\Collection;

class UserForm
{
    // Mempertahankan Schema $schema sesuai permintaan Anda
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // === BAGIAN 1: INFORMASI AKUN (Nama, Email, Password, Role, Status) ===
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
                            // LOGIKA AUTO-FILL EMAIL
                            ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                                // Hanya isi email otomatis jika email saat ini KOSONG (untuk menghindari overwrite saat Edit)
                                if ($get('email') === null && $state) {
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
                            ->readonly() // Tetap ->readonly() sesuai kode yang Anda berikan
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
                                    ->live(onBlur: true)
                                    ->validationAttribute('Password'),

                                TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->password()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(false)
                                    ->visible(fn (Get $get) => filled($get('password')) || $schema->getOperation() === 'create'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                // 4. Role Pengguna (Single Select) - PERBAIKAN UTAMA DI SINI
                                Select::make('role')
                                    ->label('Role Pengguna')
                                    ->options(fn () => \App\Models\Role::pluck('display_name', 'name'))
                                    ->required()
                                    ->placeholder('Pilih role')
                                    ->columnSpan(1),

                                // 5. Status Akun (Active)
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
                    ])
                    ->collapsible()
                    ->compact()
                    ->columnSpanFull(),

                // === BAGIAN 2: INFORMASI PRIBADI (Phone, Gender, Birth Date) ===
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
                                    ->options(['MALE' => 'Laki-laki', 'FEMALE' => 'Perempuan'])
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