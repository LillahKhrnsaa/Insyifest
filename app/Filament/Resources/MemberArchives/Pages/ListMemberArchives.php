<?php

namespace App\Filament\Resources\MemberArchives\Pages;

use App\Filament\Resources\MemberArchives\MemberArchiveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMemberArchives extends ListRecords
{
    protected static string $resource = MemberArchiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
