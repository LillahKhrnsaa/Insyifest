<?php

namespace App\Filament\Resources\FormRegistrations\Schemas;

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
                    ->required()
                    ->live(onBlur: true) // atau ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->default(true),


                Repeater::make('schedules')
                ->label('Jadwal')
                ->required()
                ->schema([

                    TextInput::make('day')
                        ->required(),

                    TimePicker::make('time')
                        ->required(),

                    DatePicker::make('date')
                        ->required(),

                    /* =====================
                     * COACHES (NESTED)
                     * ===================== */
                    Repeater::make('coaches')
                        ->label('Pelatih')
                        ->required()
                        ->schema([

                            Select::make('coach_id')
                                ->label('Coach')
                                ->options(
                                    User::query()
                                        ->role('coach') // spatie
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->required(),

                            TextInput::make('quota')
                                ->numeric()
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns(2)
                        ->minItems(1),
                ])
                ->columnSpanFull()
                ->minItems(1),
                 /* =====================
                * FORM FIELDS
                * ===================== */
                Repeater::make('fields')
                    ->label('Field Form')
                    ->required()
                    ->schema([

                        TextInput::make('label')
                            ->required(),

                        TextInput::make('name')
                            ->required()
                            ->helperText('Harus unik, contoh: nama_lengkap'),

                        Select::make('type')
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
                            ->helperText('Khusus select / checkbox')
                            ->visible(fn ($get) => in_array($get('type'), ['select', 'checkbox'])),
                    ])
                    ->columnSpanFull()
                    ->minItems(1),

            ]);
    }
}
