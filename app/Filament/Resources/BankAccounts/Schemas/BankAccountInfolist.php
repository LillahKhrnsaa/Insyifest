<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class BankAccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Bank Account Details')
                ->icon('heroicon-o-banknotes')
                ->description('Informasi detail rekening bank.')
                ->schema([
                    Grid::make()
                        ->schema([
                            TextEntry::make('bank_name')
                                ->label('Bank Name')
                                ->weight('medium')
                                ->icon('heroicon-o-building-library')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),

                            TextEntry::make('account_number')
                                ->label('Account Number')
                                ->copyable()
                                ->copyMessage('Nomor rekening disalin!')
                                ->icon('heroicon-o-credit-card')
                                ->color('primary')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),

                            TextEntry::make('account_holder')
                                ->label('Account Holder')
                                ->icon('heroicon-o-user-circle')
                                ->color('gray')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),

                            IconEntry::make('is_active')
                                ->label('Status')
                                ->boolean()
                                ->trueIcon('heroicon-s-check-circle')
                                ->falseIcon('heroicon-s-x-circle')
                                ->trueColor('success')
                                ->falseColor('danger')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),
                        ]),
                ])
                ->columnSpanFull(),

            Section::make('Informasi Lanjutan')
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make()
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Created At')
                                ->dateTime('d M Y')
                                ->icon('heroicon-o-calendar')
                                ->placeholder('-')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),

                            TextEntry::make('updated_at')
                                ->label('Updated At')
                                ->dateTime('d M Y')
                                ->icon('heroicon-o-arrow-path')
                                ->placeholder('-')
                                ->alignment('start')
                                ->extraAttributes(['class' => 'py-1']),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }
}
