<?php

namespace App\Filament\Resources\GeneralMaterials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;


class GeneralMaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50) // Batasi panjang judul
                    ->tooltip(fn ($record) => $record->title)
                    ->toggleable(), // Biarkan default wrap

                // Kolom File yang Lebih Efisien (Menggabungkan Gambar & Video/Dokumen)
                TextColumn::make('file_path')
                    ->label('File')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => $record->file_path ? pathinfo($record->file_path, PATHINFO_EXTENSION) : 'N/A') // Ambil ekstensi
                    ->icon(fn ($state) => match (strtolower($state)) { // Ikon berdasarkan ekstensi
                        'jpg', 'jpeg', 'png', 'webp', 'gif' => 'heroicon-o-photo',
                        'mp4', 'mov', 'webm' => 'heroicon-o-video-camera',
                        default => 'heroicon-o-document',
                    })
                    ->color('gray')
                    ->badge(fn ($state) => $state !== 'N/A') // Tampilkan sebagai badge jika ada file
                    ->tooltip('Klik untuk melihat/download file')
                    ->url(fn ($record) => $record->file_path ? URL::asset('storage/' . $record->file_path) : null)
                    ->openUrlInNewTab()
                    ->toggleable(),

                // Kolom Status menggunakan Badge yang Modern
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'archived' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Active',
                        'archived' => 'Archived',
                        default => ucfirst($state),
                    })
                    ->sortable()
                    ->toggleable(),

                // Kolom Tanggal Otomatis di Hidden
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y') // Format tanggal yang lebih ringkas
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Terakhir Update')
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
