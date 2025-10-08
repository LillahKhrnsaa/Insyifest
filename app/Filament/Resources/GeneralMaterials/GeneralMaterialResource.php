<?php

namespace App\Filament\Resources\GeneralMaterials;

use App\Filament\Resources\GeneralMaterials\Pages\CreateGeneralMaterial;
use App\Filament\Resources\GeneralMaterials\Pages\EditGeneralMaterial;
use App\Filament\Resources\GeneralMaterials\Pages\ListGeneralMaterials;
use App\Filament\Resources\GeneralMaterials\Pages\ViewGeneralMaterial;
use App\Filament\Resources\GeneralMaterials\Schemas\GeneralMaterialForm;
use App\Filament\Resources\GeneralMaterials\Schemas\GeneralMaterialInfolist;
use App\Filament\Resources\GeneralMaterials\Tables\GeneralMaterialsTable;
use App\Models\GeneralMaterial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralMaterialResource extends Resource
{
    protected static ?string $model = GeneralMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return GeneralMaterialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GeneralMaterialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneralMaterialsTable::configure($table);
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
            'index' => ListGeneralMaterials::route('/'),
        ];
    }
}
