<?php

namespace App\Filament\Resources\TrainingPackages;

use App\Filament\Resources\TrainingPackages\Pages\CreateTrainingPackage;
use App\Filament\Resources\TrainingPackages\Pages\EditTrainingPackage;
use App\Filament\Resources\TrainingPackages\Pages\ListTrainingPackages;
use App\Filament\Resources\TrainingPackages\Pages\ViewTrainingPackage;
use App\Filament\Resources\TrainingPackages\Schemas\TrainingPackageForm;
use App\Filament\Resources\TrainingPackages\Schemas\TrainingPackageInfolist;
use App\Filament\Resources\TrainingPackages\Tables\TrainingPackagesTable;
use App\Models\TrainingPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingPackageResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document';
    }

    public static function getNavigationLabel(): string
    {
        return 'Paket Latihan';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Managemen Training';
    }
    protected static ?string $model = TrainingPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TrainingPackageForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TrainingPackageInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingPackagesTable::configure($table);
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
            'index' => ListTrainingPackages::route('/'),
        ];
    }
}
