<?php

namespace App\Filament\Resources\TrainingSchedules\Pages;

use App\Filament\Resources\TrainingSchedules\TrainingScheduleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingSchedule extends ViewRecord
{
    protected static string $resource = TrainingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
