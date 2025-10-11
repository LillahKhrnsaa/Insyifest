<?php

namespace App\Filament\Resources\PaymentHistories;

use App\Filament\Resources\PaymentHistories\Pages\CreatePaymentHistory;
use App\Filament\Resources\PaymentHistories\Pages\EditPaymentHistory;
use App\Filament\Resources\PaymentHistories\Pages\ListPaymentHistories;
use App\Filament\Resources\PaymentHistories\Pages\ViewPaymentHistory;
use App\Filament\Resources\PaymentHistories\Schemas\PaymentHistoryForm;
use App\Filament\Resources\PaymentHistories\Schemas\PaymentHistoryInfolist;
use App\Filament\Resources\PaymentHistories\Tables\PaymentHistoriesTable;
use App\Models\PaymentHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentHistoryResource extends Resource
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Finance & Payment';
    }
    protected static ?string $model = PaymentHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PaymentHistoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentHistoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentHistoriesTable::configure($table);
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
            'index' => ListPaymentHistories::route('/'),
        ];
    }
}
