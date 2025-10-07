<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Bank Central Asia (BCA)'),

                TextInput::make('account_number')
                    ->label('No Rekening')
                    ->required()
                    ->minLength(6)
                    ->maxLength(30)
                    ->rules(['regex:/^[0-9]+$/'])
                    ->unique(ignorable: fn ($record) => $record)
                    ->helperText('Hanya angka, tanpa spasi atau tanda'),

                TextInput::make('account_holder')
                    ->label('Nama Pemilik Rekening')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Nama pemilik rekening')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
