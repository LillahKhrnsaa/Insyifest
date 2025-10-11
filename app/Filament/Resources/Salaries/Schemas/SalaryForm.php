<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Salary Details')
                ->description('Isi data gaji pelatih dengan lengkap dan akurat.')
                ->icon('heroicon-o-currency-dollar')
                ->collapsible()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('coach_id')
                                ->label('Coach')
                                ->relationship('coach', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->hintIcon('heroicon-o-user-circle')
                                ->helperText('Pilih pelatih yang menerima gaji.'),

                            TextInput::make('amount')
                                ->label('Amount (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->inputMode('decimal')
                                ->suffixIcon('heroicon-o-banknotes')
                                ->required()
                                ->helperText('Masukkan jumlah gaji yang dibayarkan.'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            TextInput::make('month')
                                ->label('Month')
                                ->placeholder('Contoh: October 2025')
                                ->suffixIcon('heroicon-o-calendar-days')
                                ->required()
                                ->helperText('Isi bulan pembayaran gaji.'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'paid' => '✅ Paid',
                                    'pending' => '🕓 Pending',
                                    'unpaid' => '❌ Unpaid',
                                ])
                                ->default('paid')
                                ->required()
                                ->suffixIcon('heroicon-o-flag')
                                ->helperText('Pilih status pembayaran gaji.'),
                        ]),

                    DatePicker::make('paid_at')
                        ->label('Paid At')
                        ->native(false)
                        ->displayFormat('d M Y')
                        ->suffixIcon('heroicon-o-calendar')
                        ->helperText('Tanggal gaji dibayarkan.'),
                ]),
            ]);
    }
}
