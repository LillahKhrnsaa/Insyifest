<?php

namespace App\Filament\Resources\PengaturanAdmins;

use App\Filament\Resources\PengaturanAdmins\Pages\ManagePengaturanAdmins;
use App\Filament\Resources\PengaturanAdmins\Schemas\PengaturanAdminForm;
use App\Filament\Resources\PengaturanAdmins\Tables\PengaturanAdminsTable;
use App\Models\CoachSchedule;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengaturanAdminResource extends Resource
{
    protected static ?string $model = CoachSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan Jadwal & Kuota';
    }

    public static function getPluralLabel(): string
    {
        return 'Pengaturan';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Training';
    }

    public static function form(Schema $schema): Schema
    {
        return PengaturanAdminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengaturanAdminsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePengaturanAdmins::route('/'),
        ];
    }
}
