<?php

namespace App\Filament\Resources\GeneralMaterials\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Filament\Support\Enums\IconSize;


class GeneralMaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->description('Detail utama dan deskripsi materi umum.')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul')
                            ->weight('bold'),

                        TextEntry::make('status')
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
                            }),
                            
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tidak ada deskripsi.')
                            ->markdown()
                            ->wrap()
                            ->columnSpanFull(),
                    ])->columnSpanFull()->columns(2)->collapsible(),
                
                Section::make('Keterangan Waktu')
                    ->description('Informasi tentang kapan materi dibuat dan terakhir diperbarui.')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->icon('heroicon-o-calendar')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Update')
                            ->icon('heroicon-o-arrow-path')
                            ->dateTime('d M Y, H:i'),
                    ])->columnSpanFull()->columns(2)->collapsible(),

                Section::make('Pratinjau File')
                    ->description('Tampilkan thumbnail atau ikon file. Klik untuk download.')
                    ->schema([
                        ImageEntry::make('file_path') // ImageEntry untuk gambar/thumbnail
                            ->label('Preview Gambar')
                            ->hidden(fn ($record) => !Str::of($record->file_path)->endsWith(['jpg','jpeg','png','webp','gif']))
                            ->disk('public')
                            ->imageHeight(500)
                            ->imageWidth(500),

                        IconEntry::make('file_path') // IconEntry untuk non-gambar (Video/Dokumen)
                            ->label('Tipe File')
                            ->hidden(fn ($record) => Str::of($record->file_path)->endsWith(['jpg','jpeg','png','webp','gif']))
                            ->icon(fn ($record) => match (strtolower(pathinfo($record->file_path, PATHINFO_EXTENSION))) {
                                'mp4','mov','webm' => 'heroicon-o-video-camera',
                                'pdf' => 'heroicon-o-document-text',
                                'zip','rar' => 'heroicon-o-archive-box',
                                default => 'heroicon-o-document',
                            })
                            ->color('primary')
                            ->size(IconSize::ExtraLarge),

                        TextEntry::make('file_path')
                            ->label('Lihat File')
                            ->formatStateUsing(fn ($state) => pathinfo($state, PATHINFO_BASENAME)) // Tampilkan hanya nama file
                            ->url(fn ($record) => $record->file_path ? URL::asset('storage/' . $record->file_path) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->placeholder('Tidak ada file terlampir.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()->collapsible(),
            ]);
    }
}
