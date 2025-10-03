<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Permission Details')
                    ->columns(2)
                    ->description('Berikan nama dan deskripsi untuk permission ini.')
                    ->schema([
                        TextInput::make('display_name')
                            ->label('Nama Permission')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Edit Articles')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! empty($state)) {
                                    $set('name', Str::slug($state,'.'));
                                }
                            }),

                        TextInput::make('name')
                            ->label('Permission')
                            ->placeholder('otomatis terisi berdasarkan nama permission')
                            ->maxLength(255)
                            ->readOnly(),

                        Textarea::make('description')
                            ->label('Deskripsi Permission')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Hidden::make('guard_name')
                            ->default('web'),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
