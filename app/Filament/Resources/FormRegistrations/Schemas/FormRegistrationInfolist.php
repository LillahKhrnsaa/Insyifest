<?php

namespace App\Filament\Resources\FormRegistrations\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FormRegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug')
                    ->label('Public URL')
                    ->url(fn ($record) => route('form.external.show', $record->slug))
                    ->openUrlInNewTab(),
                TextEntry::make('description'),
                TextEntry::make('is_active')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                /* =====================
                * SCHEDULES VIEW
                * ===================== */
                RepeatableEntry::make('schedules')
                    ->label('Jadwal')
                    ->schema([

                        TextEntry::make('day'),
                        TextEntry::make('date'),
                        TextEntry::make('time'),

                        RepeatableEntry::make('coaches')
                            ->label('Pelatih')
                            ->schema([
                                TextEntry::make('coach.name')
                                    ->label('Coach'),
                                TextEntry::make('quota'),
                                TextEntry::make('quota_used'),
                            ]),
                    ]),

                /* =====================
                * FORM FIELDS VIEW
                * ===================== */
                RepeatableEntry::make('fields')
                    ->label('Field Form')
                    ->schema([
                        TextEntry::make('label'),
                        TextEntry::make('name'),
                        TextEntry::make('type'),
                        TextEntry::make('is_required')
                            ->badge(),
                    ]),
            ]);
    }
}
