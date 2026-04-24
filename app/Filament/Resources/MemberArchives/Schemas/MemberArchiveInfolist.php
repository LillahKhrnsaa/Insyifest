<?php

namespace App\Filament\Resources\MemberArchives\Schemas;

use Filament\Schemas\Schema;

class MemberArchiveInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\Section::make('Informasi Arsip Atlet')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('archive_period')->label('Periode'),
                        \Filament\Infolists\Components\TextEntry::make('name')->label('Nama Atlet'),
                        \Filament\Infolists\Components\TextEntry::make('email')->label('Email'),
                        \Filament\Infolists\Components\TextEntry::make('phone')->label('No. HP'),
                        \Filament\Infolists\Components\TextEntry::make('training_package_name')->label('Paket Latihan'),
                        \Filament\Infolists\Components\TextEntry::make('status')->label('Status Terakhir'),
                        \Filament\Infolists\Components\TextEntry::make('start_date')->label('Tanggal Mulai')->date(),
                        \Filament\Infolists\Components\TextEntry::make('end_date')->label('Tanggal Berakhir')->date(),
                        \Filament\Infolists\Components\TextEntry::make('created_at')->label('Waktu Arsip')->dateTime(),
                    ])->columns(2)
            ]);
    }
}
