<?php

namespace App\Filament\Resources\TrainingPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TrainingPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama dengan Ikon dan Deskripsi
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-archive-box') // Ikon generik sebagai pengganti gambar
                    ->description(fn (Model $record): string => 
                        $record->description ? substr($record->description, 0, 40) . '...' : ''
                    ),

                // Kolom Harga dengan Badge
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignment('center'), // Posisikan di tengah agar rapi

                // Kolom Tanggal Dibuat
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('View details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit role')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Delete role')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->extraAttributes([
                        'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2']),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
