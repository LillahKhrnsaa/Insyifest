<?php

namespace App\Filament\Resources\MemberArchives;

use App\Filament\Resources\MemberArchives\Pages\CreateMemberArchive;
use App\Filament\Resources\MemberArchives\Pages\EditMemberArchive;
use App\Filament\Resources\MemberArchives\Pages\ListMemberArchives;
use App\Filament\Resources\MemberArchives\Pages\ViewMemberArchive;
use App\Filament\Resources\MemberArchives\Schemas\MemberArchiveForm;
use App\Filament\Resources\MemberArchives\Schemas\MemberArchiveInfolist;
use App\Filament\Resources\MemberArchives\Tables\MemberArchivesTable;
use App\Models\MemberArchive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MemberArchiveResource extends Resource
{
    protected static ?string $model = MemberArchive::class;

    public static function getNavigationLabel(): string
    {
        return 'Arsip Member Bulanan';
    }

    public static function getPluralLabel(): string
    {
        return 'Arsip Member Bulanan';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Training';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-archive-box';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('viewAny.member_archives');
    }

    public static function form(Schema $schema): Schema
    {
        return MemberArchiveForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MemberArchiveInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MemberArchivesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberArchives::route('/'),
            'create' => CreateMemberArchive::route('/create'),
            'view' => ViewMemberArchive::route('/{record}'),
            'edit' => EditMemberArchive::route('/{record}/edit'),
        ];
    }
}
