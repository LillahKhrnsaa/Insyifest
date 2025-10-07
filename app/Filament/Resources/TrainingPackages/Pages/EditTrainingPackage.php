<?php

namespace App\Filament\Resources\TrainingPackages\Pages;

use App\Filament\Resources\TrainingPackages\TrainingPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingPackage extends EditRecord
{
    protected static string $resource = TrainingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
