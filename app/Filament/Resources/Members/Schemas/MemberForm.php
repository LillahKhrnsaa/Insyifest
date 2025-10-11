<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Str; 

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Akun Pengguna')
                    ->description('Detail login dan akses untuk member.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        // ✅ PERUBAHAN DI SINI: Logika 'afterStateUpdated' disederhanakan
                        TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set) {
                                if ($state) {
                                    $baseEmail = Str::lower(str_replace(' ', '.', $state));
                                    $set('email', $baseEmail . '@cikampekswimmingclub.com');
                                }
                            })->columnSpanFull(),

                        // ✅ PERUBAHAN DI SINI: Tambahkan 'readonly' agar konsisten dengan Coach
                        TextInput::make('email')
                        ->label('Alamat Email')
                            ->email()->required()->prefixIcon('heroicon-o-envelope')
                            ->readonly() // Buat field ini tidak bisa diubah manual
                            ->helperText('Email akan terisi otomatis berdasarkan nama.')
                            ->columnSpanFull()
                            ->placeholder('contoh@cikampekswimmingclub.gmail.com')
                            ->extraAttributes(['class' => 'focus:border-primary-500'])
                            ->columnSpanFull(),
                        
                        // ... sisa field di section ini sama ...
                        Grid::make(2)->schema([
                            TextInput::make('password')->label('Password')
                                ->password()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(fn ($state) => filled($state))
                                ->minLength(8)->revealable()->confirmed(),
                            TextInput::make('password_confirmation')->label('Konfirmasi Password')
                                ->password()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrated(false),
                        ]),
                        FileUpload::make('photo_path')->label('Foto Profil')
                            ->image()->avatar()->disk('public')->directory('member-photos')
                            ->imageEditor()->columnSpanFull(),
                        Toggle::make('active')->label('Akun Aktif')->required()->default(true),
                        Hidden::make('role')->default('member'),
                    ])->collapsible()->columns(2),

                // ... Sisa Section lainnya tidak ada perubahan ...
                Section::make('Informasi Pribadi')
                    ->description('Data diri member.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TextInput::make('phone')->label('Nomor Telepon')->tel()->required()->prefixIcon('heroicon-o-phone'),
                        Select::make('gender')->label('Jenis Kelamin')
                            ->options(['MALE' => 'Laki-laki', 'FEMALE' => 'Perempuan'])
                            ->native(false)->prefixIcon('heroicon-o-user'),
                        DatePicker::make('birth_date')->label('Tanggal Lahir')
                            ->maxDate(now())->native(false)->prefixIcon('heroicon-o-cake'),
                    ])->collapsible()->columns(3),

                Section::make('Informasi Keanggotaan')
                    ->description('Detail spesifik terkait status keanggotaan.')
                    ->icon('heroicon-o-ticket')
                    ->schema([
                        Select::make('training_package_id')->label('Paket Latihan')
                            ->relationship('trainingPackage', 'name')->searchable()->preload()->native(false),
                        Select::make('status')->label('Status Keanggotaan')
                            ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif'])
                            ->required()->default('AKTIF')->native(false),
                        DatePicker::make('start_date')->label('Tanggal Mulai')
                            ->required()->default(now())->native(false),
                        DatePicker::make('end_date')->label('Tanggal Berakhir')->native(false),
                    ])->collapsible()->columns(2),
            ]);
    }
}
