<?php

namespace App\Filament\Resources\PengaturanAdmins\Pages;

use App\Filament\Resources\PengaturanAdmins\PengaturanAdminResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePengaturanAdmins extends ManageRecords
{
    protected static string $resource = PengaturanAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat ' . static::getResource()::getNavigationLabel()),
        ];
    }
}
