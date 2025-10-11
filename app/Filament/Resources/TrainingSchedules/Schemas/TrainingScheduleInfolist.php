<?php

namespace App\Filament\Resources\TrainingSchedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TrainingScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('day'),
                TextEntry::make('time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('place')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
