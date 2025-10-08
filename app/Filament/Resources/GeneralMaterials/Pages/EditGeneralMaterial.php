<?php

namespace App\Filament\Resources\GeneralMaterials\Pages;

use App\Filament\Resources\GeneralMaterials\GeneralMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGeneralMaterial extends EditRecord
{
    protected static string $resource = GeneralMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
