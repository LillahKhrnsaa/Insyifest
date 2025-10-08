<?php

namespace App\Filament\Resources\GeneralMaterials\Pages;

use App\Filament\Resources\GeneralMaterials\GeneralMaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGeneralMaterials extends ListRecords
{
    protected static string $resource = GeneralMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
