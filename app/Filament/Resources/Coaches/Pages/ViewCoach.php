<?php

namespace App\Filament\Resources\Coaches\Pages;

use App\Filament\Resources\Coaches\CoachResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCoach extends ViewRecord
{
    protected static string $resource = CoachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
