<?php

namespace App\Filament\Resources\TrainingPackages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class TrainingPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Package Information')
                ->description('Fill in the details of the training package.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Package Name')
                            ->placeholder('e.g., Web Development Masterclass')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR')
                            ->minValue(0)
                            ->helperText('Enter the price in Indonesian Rupiah.'),
                    ]),

                    Textarea::make('description')
                        ->label('Package Description')
                        ->placeholder('Provide a brief description of what this package includes.')
                        ->rows(5)
                        ->columnSpanFull(),
                ])->columnSpanFull(),
            ]);
    }
}
