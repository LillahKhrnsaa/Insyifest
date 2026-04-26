<?php

namespace App\Filament\Resources\MemberArchives\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class MemberArchivesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex(),

                \Filament\Tables\Columns\TextColumn::make('archive_period')
                    ->label('Periode')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Nama Atlet')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('training_package_name')
                    ->label('Paket Latihan')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status Terakhir')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'TIDAK_AKTIF' => 'danger',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Arsip')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('archive_period')
                    ->label('Filter Periode')
                    ->options(fn () => \App\Models\MemberArchive::distinct()->pluck('archive_period', 'archive_period')->toArray()),
            ])
            ->headerActions([
                Action::make('download_pdf_list')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function () {
                        $records = \App\Models\MemberArchive::all();
                        $pdf = Pdf::loadView('pdf.member-archive-list', [
                            'records' => $records,
                            'title' => 'Daftar Arsip Member'
                        ])->setPaper('a4', 'landscape');
                        
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'daftar-arsip-member-' . date('Y-m-d') . '.pdf');
                    }),

                Action::make('download_csv')
                    ->label('Export Excel (CSV)')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(function () {
                        $records = \App\Models\MemberArchive::all();
                        $csvFileName = 'arsip_member_' . date('Y-m-d_H-i-s') . '.csv';
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
                        ];

                        $callback = function () use ($records) {
                            $file = fopen('php://output', 'w');
                            fputcsv($file, ['Periode', 'Nama Atlet', 'Email', 'No. HP', 'Paket Latihan', 'Status Terakhir', 'Tanggal Mulai', 'Tanggal Berakhir']);

                            foreach ($records as $record) {
                                fputcsv($file, [
                                    $record->archive_period,
                                    $record->name,
                                    $record->email,
                                    $record->phone,
                                    $record->training_package_name,
                                    $record->status,
                                    $record->start_date?->format('Y-m-d'),
                                    $record->end_date?->format('Y-m-d'),
                                ]);
                            }
                            fclose($file);
                        };

                        return response()->stream($callback, 200, $headers);
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('')
                    ->tooltip('Lihat Detail')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes(['class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-2 py-1']),

                EditAction::make()
                    ->label('')
                    ->tooltip('Edit Data')
                    ->button()
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes(['class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-2 py-1']),
                
                Action::make('print_pdf')
                    ->label('')
                    ->tooltip('Print Dokumen')
                    ->button()
                    ->icon('heroicon-o-printer')
                    ->color('danger')
                    ->size('sm')
                    ->extraAttributes(['class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-2 py-1'])
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.member-archive', [
                            'archive' => $record,
                        ]);
                        
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'arsip-' . Str::slug($record->name) . '.pdf');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
