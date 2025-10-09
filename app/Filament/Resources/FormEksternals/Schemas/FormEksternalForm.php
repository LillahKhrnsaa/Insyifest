<?php

namespace App\Filament\Resources\FormEksternals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;

class FormEksternalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                ->label('Judul Form')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3)
                ->columnSpanFull(),

            TextInput::make('slug')
                ->label('Slug (URL)')
                ->disabled()
                ->hint('Slug otomatis dibuat dari judul'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'ACTIVE' => 'Aktif',
                    'INACTIVE' => 'Non-Aktif',
                ])
                ->default('INACTIVE')
                ->required(),

            Repeater::make('fields')
                ->label('Field Dinamis')
                ->schema([
                    TextInput::make('label')
                        ->label('Label Field')
                        ->placeholder('Contoh: Nama Lengkap')
                        ->required()
                        ->reactive(),

                    // name jadi hidden aja, karena auto dari label
                    TextInput::make('name')
                        ->hidden(),

                    Select::make('type')
                        ->label('Tipe Field')
                        ->options([
                            'text' => 'Text',
                            'textarea' => 'Textarea',
                            'email' => 'Email',
                            'number' => 'Number',
                            'date' => 'Date',
                            'datetime' => 'Datetime',
                            'select' => 'Select (Pilih Satu)',
                            'select_multiple' => 'Select (Pilih Banyak)',
                            'checkbox' => 'Checkbox (Bisa Pilih Banyak)',
                            'radio' => 'Radio (Pilih Satu)',
                            'file' => 'File Upload',
                            'url' => 'URL',
                            'password' => 'Password',
                            'tel' => 'Nomor Telepon',
                        ])
                        ->required(),


                    Textarea::make('options')
                        ->label('Opsi (untuk select, select_multiple, checkbox, radio)')
                        ->rows(2)
                        ->helperText('Pisahkan dengan koma, contoh: Ya,Tidak,Mungkin')
                        ->visible(fn ($get) => in_array($get('type'), ['select', 'select_multiple', 'checkbox', 'radio'])),


                    TextInput::make('placeholder')
                        ->label('Placeholder')
                        ->placeholder('Masukkan teks placeholder'),

                    Toggle::make('required')
                        ->label('Wajib Diisi')
                        ->default(false),

                    Toggle::make('show_in_list')
                        ->label('Tampilkan di list hasil')
                        ->default(true),
                ])
                ->columns(2)
                ->reorderable()
                ->collapsed(false)
                ->default([]),
            ]);
    }
}
