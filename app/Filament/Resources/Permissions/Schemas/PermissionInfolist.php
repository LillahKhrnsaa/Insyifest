<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class PermissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Role')
                
                ->description('Informasi lengkap tentang role')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('name')
                                ->label('Permission')
                                ->badge()
                                ->color('primary')
                                ->icon('heroicon-o-tag'),

                            TextEntry::make('guard_name')
                                ->label('Guard')
                                ->badge()
                                ->color('blue')
                                ->icon('heroicon-o-shield-check'),

                            TextEntry::make('display_name')
                                ->label('Nama Permission')
                                ->color('gray-800')
                                ->weight('font-bold')
                                ->icon('heroicon-o-identification')
                                ->iconColor('green')
                                ->size('text-xl'),
                        ]),

                    Section::make('description')
                        ->description('Penjelasan detail tentang role')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            TextEntry::make('description')
                                ->label('')
                                ->placeholder('Tidak ada deskripsi yang ditambahkan')
                                ->color('gray-700')
                                ->prose()
                                ->markdown()
                                ->html()
                                ->extraAttributes(['class' => 'text-justify leading-relaxed']),
                        ])
                        ->compact()
                        ->collapsible(),

                    Section::make('Timestamps')
                        ->description('Inform waktu pembuatan dan update')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->label('Dibuat')
                                        ->dateTime('d M Y, H:i')
                                        ->icon('heroicon-o-plus-circle')
                                        ->iconColor('success')
                                        ->color('gray-600')
                                        ->since()
                                        ->tooltip('Waktu dibuat: {state}'),

                                    TextEntry::make('updated_at')
                                        ->label('Diupdate')
                                        ->dateTime('d M Y, H:i')
                                        ->icon('heroicon-o-arrow-path')
                                        ->iconColor('warning')
                                        ->color('gray-600')
                                        ->since()
                                        ->tooltip('Waktu update: {state}'),
                                ]),
                        ])
                        ->compact()
                        ->collapsible(),
                ])->columnSpanFull(),
            ]);

        
    }
}
