<?php

namespace App\Filament\Resources\FormEksternals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FormEksternalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('fields')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('INACTIVE'),
            ]);
    }
}
