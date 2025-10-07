<?php

namespace App\Filament\Resources\BankAccounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BankAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->icon('heroicon-o-building-library')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->formatStateUsing(fn (string $state) => '******' . substr($state, -4))
                    ->tooltip(fn (string $state) => "Nomor asli: {$state}")
                    ->copyable()
                    ->copyMessage('Nomor rekening disalin!')
                    ->color('primary'),

                TextColumn::make('account_holder')
                    ->label('Account Holder')
                    ->icon('heroicon-o-user')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Active' : 'Inactive'),

                TextColumn::make('created_at')
                    ->label('Created On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->label('Updated On')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active Status'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}