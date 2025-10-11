<?php

namespace App\Filament\Resources\TrainingSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Table;
use Filament\Actions\Action;

use Filament\Notifications\Notification;

class TrainingSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. HARI LATIHAN (Badge dengan Terjemahan)
                TextColumn::make('day')
                    ->badge()
                    ->label('Hari Latihan')
                    // Warna badge berdasarkan hari untuk estetika
                    ->colors([
                        'success' => 'SENIN',
                        'warning' => 'SELASA',
                        'info' => 'RABU',
                        'primary' => 'KAMIS',
                        'danger' => 'JUMAT',
                        'secondary' => 'SABTU',
                        'gray' => 'MINGGU',
                    ])
                    // Mengubah nilai DB (MONDAY) ke tampilan (Senin)
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'SENIN' => 'Senin',
                        'SELASA' => 'Selasa',
                        'RABU' => 'Rabu',
                        'KAMIS' => 'Kamis',
                        'JUMAT' => 'Jumat',
                        'SABTU' => 'Sabtu',
                        'MINGGU' => 'Minggu',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),

                // 2. WAKTU MULAI
                TextColumn::make('time')
                    ->label('Waktu Mulai')
                    ->time('H:i') // Format jam 24h
                    ->icon('heroicon-o-clock')
                    ->sortable(),

                // 3. TEMPAT LATIHAN
                TextColumn::make('place')
                    ->label('Lokasi Kolam')
                    ->icon('heroicon-o-map-pin')
                    ->searchable(),

                // 5. META DATA (Tanggal Dibuat)
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 6. META DATA (Tanggal Diperbarui)
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter Hari bisa ditambahkan di sini nanti
            ])
            ->recordActions([
                Action::make('viewCoaches')
                    ->label('Lihat Coach')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'Coach Bertugas')
                    ->modalDescription(fn ($record) => $record->day . ' - ' . $record->time . ' - ' . $record->place)
                    ->infolist(function ($record): array {
                        if ($record->coaches->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('empty')
                                            ->label('')
                                            ->state('Belum ada coach yang ditugaskan untuk jadwal ini.')
                                            ->color('warning'),
                                    ]),
                            ];
                        }

                        return [
                            Section::make('Daftar Coach')
                                ->schema([
                                    RepeatableEntry::make('coaches')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('Nama Coach')
                                                ->weight('bold')
                                                ->icon('heroicon-o-user'),
                                            TextEntry::make('email')
                                                ->label('Email')
                                                ->icon('heroicon-o-envelope')
                                                ->copyable(),
                                            TextEntry::make('bio')
                                                ->label('Bio')
                                                ->icon('heroicon-o-document-text')
                                                ->default('-')
                                                ->limit(150),
                                        ])
                                        ->columns(3)
                                        ->columnSpanFull(),
                                ])
                                ->collapsible()
                                ->collapsed(false),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->slideOver(),
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
