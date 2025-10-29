<?php

namespace App\Filament\Resources\Salaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\DeleteAction;

class SalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🧑‍🏫 Nama Pelatih
                TextColumn::make('coach.user.name')
                    ->label('Pelatih')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->icon('heroicon-o-user-circle')
                    ->description(fn ($record) => $record->coach?->user?->email),

                // 📅 Periode
                TextColumn::make('month')
                    ->label('Periode')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->badge()
                    ->color('info'),

                // 👥 Jumlah Atlet (Virtual)
                TextColumn::make('member_count')
                    ->label('Atlet')
                    ->state(function ($record) {
                        return $record->coach?->members()->count() ?? 0;
                    })
                    ->icon('heroicon-o-user-group')
                    ->alignCenter()
                    ->sortable(false),

                // 🏋️ Pertemuan
                TextColumn::make('training_sessions')
                    ->label('Pertemuan')
                    ->numeric()
                    ->alignCenter()
                    ->sortable()
                    ->icon('heroicon-o-academic-cap'),

                // 💰 Total Gaji
                TextColumn::make('total_amount')
                    ->label('Total Gaji')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->icon('heroicon-o-banknotes')
                    ->summarize([
                        \Filament\Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total Keseluruhan'),
                    ]),

                // 📋 Status
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                        default => $state,
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'paid' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),

                // 📅 Tanggal Bayar
                TextColumn::make('paid_at')
                    ->label('Tgl Bayar')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('—')
                    ->icon('heroicon-o-calendar-days')
                    ->toggleable(),

                // 🕒 Timestamps
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter by Status
                SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                    ])
                    ->placeholder('Semua Status'),

                // Filter by Month
                Filter::make('month')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('month')
                            ->label('Periode')
                            ->placeholder('Contoh: Oktober 2025'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['month'] ?? null,
                            fn (Builder $query, $month): Builder => $query->where('month', 'like', "%{$month}%"),
                        );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->extraAttributes([
                        'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2']),

                // 📄 Export PDF Action
                Action::make('exportPdf')
                    ->label(' ')
                    ->button()
                    ->tooltip('Print')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function ($record) {
                        return response()->streamDownload(function () use ($record) {
                            // Load relasi yang diperlukan
                            $record->load('coach.user');
                            
                            // Hitung member count
                            $memberCount = $record->coach?->members()->count() ?? 0;
                            
                            // Generate PDF
                            $pdf = Pdf::loadView('pdf.salary-slip', [
                                'salary' => $record,
                                'memberCount' => $memberCount,
                            ]);
                            
                            echo $pdf->stream();
                        }, 'slip-gaji-' . $record->coach?->user?->name . '-' . $record->month . '.pdf');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Data Gaji')
            ->emptyStateDescription('Tambahkan data gaji pelatih untuk memulai.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}