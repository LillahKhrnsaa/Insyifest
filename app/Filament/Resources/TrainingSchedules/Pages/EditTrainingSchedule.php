<?php

namespace App\Filament\Resources\TrainingSchedules\Pages;

use App\Filament\Resources\TrainingSchedules\TrainingScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingSchedule extends EditRecord
{
    protected static string $resource = TrainingScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
