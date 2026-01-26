<?php

namespace App\Filament\Resources\FormRegistrations\Schemas;

use App\Models\Coach;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FormRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Form')
                    ->placeholder('Contoh: Pendaftaran Atlet U-12')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    )
                    ->columnSpan(2),

                TextInput::make('slug')
                    ->label('Slug / URL')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Otomatis dibuat dari nama form')
                    ->columnSpan(2),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Deskripsi singkat mengenai form pendaftaran')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Status Form')
                    ->default(true)
                    ->helperText('Nonaktifkan jika form tidak bisa diakses publik'),

                Repeater::make('schedules')
                    ->label('Jadwal Pendaftaran')
                    ->required()
                    ->minItems(1)
                    ->schema([

                        TextInput::make('day')
                            ->label('Hari')
                            ->placeholder('Senin')
                            ->required(),

                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required(),

                        TimePicker::make('time')
                            ->label('Jam')
                            ->required(),

                        Repeater::make('coaches')
                            ->label('Pelatih')
                            ->required()
                            ->minItems(1)
                            ->schema([

                                Select::make('coach_id')
                                    ->label('Coach')
                                    ->options(
                                        Coach::query()
                                            ->join('users', 'users.id', '=', 'coaches.user_id')
                                            ->orderBy('users.name')
                                            ->pluck('users.name', 'coaches.id')
                                    )
                                    ->searchable()
                                    ->placeholder('Pilih coach')
                                    ->required(),

                                TextInput::make('quota')
                                    ->label('Kuota')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Contoh: 10')
                                    ->required(),
                            ])
                            ->columnSpanFull()
                            ->columns(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Repeater::make('fields')
                    ->label('Field Pendaftaran')
                    ->required()
                    ->minItems(1)
                    ->schema([

                        TextInput::make('label')
                            ->label('Label')
                            ->placeholder('Nama Lengkap')
                            ->required(),

                        TextInput::make('name')
                            ->label('Nama Field')
                            ->placeholder('nama_lengkap')
                            ->helperText('Harus unik, tanpa spasi')
                            ->required(),

                        Select::make('type')
                            ->label('Tipe Input')
                            ->required()
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'select' => 'Select',
                                'checkbox' => 'Checkbox',
                            ]),

                        Checkbox::make('is_required')
                            ->label('Wajib diisi'),

                        Textarea::make('options')
                            ->label('Options (JSON)')
                            ->rows(3)
                            ->helperText('Contoh: ["A","B","C"]')
                            ->visible(fn ($get) =>
                                in_array($get('type'), ['select', 'checkbox'])
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ])
            ->columns(4);
    }
}
