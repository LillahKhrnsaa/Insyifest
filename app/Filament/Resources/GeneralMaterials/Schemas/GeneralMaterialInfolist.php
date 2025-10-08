<?php

namespace App\Filament\Resources\GeneralMaterials\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GeneralMaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('file_path')
                    ->placeholder('-'),
                TextEntry::make('uploaded_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
