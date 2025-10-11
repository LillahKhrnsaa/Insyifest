<?php

namespace App\Filament\Resources\Salaries;

use App\Filament\Resources\Salaries\Pages\CreateSalary;
use App\Filament\Resources\Salaries\Pages\EditSalary;
use App\Filament\Resources\Salaries\Pages\ListSalaries;
use App\Filament\Resources\Salaries\Pages\ViewSalary;
use App\Filament\Resources\Salaries\Schemas\SalaryForm;
use App\Filament\Resources\Salaries\Schemas\SalaryInfolist;
use App\Filament\Resources\Salaries\Tables\SalariesTable;
use App\Models\Salary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Finance & Payment';
    }

    protected static ?string $model = Salary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SalaryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalaryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalariesTable::configure($table);
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
            'index' => ListSalaries::route('/'),
            'create' => CreateSalary::route('/create'),
            'view' => ViewSalary::route('/{record}'),
            'edit' => EditSalary::route('/{record}/edit'),
        ];
    }
}
