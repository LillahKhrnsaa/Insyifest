<?php

namespace App\Filament\Resources\GeneralMaterials\Pages;

use App\Filament\Resources\GeneralMaterials\GeneralMaterialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGeneralMaterial extends ViewRecord
{
    protected static string $resource = GeneralMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
