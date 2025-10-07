<?php

namespace App\Filament\Resources\TrainingPackages\Pages;

use App\Filament\Resources\TrainingPackages\TrainingPackageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingPackage extends ViewRecord
{
    protected static string $resource = TrainingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
