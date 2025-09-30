<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                    ->columns(2)
                    ->description('Berikan nama dan deskripsi untuk role ini.')
                    ->schema([
                        TextInput::make('display_name')
                            ->label('Nama Role')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Admin')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, $state) {
                                if (! empty($state)) {
                                    $set('name', Str::slug($state,'_'));
                                }
                            }),

                        TextInput::make('name')
                            ->label('Role')
                            ->placeholder('otomatis terisi berdasarkan nama role')
                            ->maxLength(255)
                            ->readOnly(),

                        Hidden::make('guard_name')
                            ->default('web'),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
