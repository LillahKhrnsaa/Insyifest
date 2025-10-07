<?php

namespace App\Filament\Resources\TrainingPackages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TrainingPackageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Package Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('name')
                                ->label('Package Name')
                                ->copyable()
                                ->copyableState(fn (string $state): string => "Package: {$state}"),

                            TextEntry::make('price')
                                ->label('Price')
                                ->money('IDR')
                                ->badge(),

                            TextEntry::make('created_at')
                                ->label('Created On')
                                ->dateTime('d M Y, H:i'),

                            TextEntry::make('updated_at')
                                ->label('Last Updated')
                                ->since(),
                        ]),
                    ])->columnSpanFull(),

                Section::make('Description')
                    ->schema([
                        TextEntry::make('description')
                            ->placeholder('No description provided.')
                            ->columnSpanFull(),
                    ])->collapsible()->columnSpanFull(),
            ]);
    }
}
