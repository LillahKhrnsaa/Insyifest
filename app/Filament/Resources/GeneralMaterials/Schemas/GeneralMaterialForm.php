<?php

namespace App\Filament\Resources\GeneralMaterials\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class GeneralMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Section::make('Materi Utama')
                        ->description('Isi detail utama dan deskripsi lengkap dari materi umum di sini.')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Materi')
                                ->placeholder('Contoh: Panduan Keselamatan Kerja K3')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->columnSpanFull(),

                            RichEditor::make('description')
                                ->label('Deskripsi Lengkap')
                                ->required()
                                ->columnSpanFull()
                                ->fileAttachmentsDirectory('general-materials/attachments')
                                ->fileAttachmentsVisibility('public')
                                ->default(null) // Sesuaikan dengan skema nullable(), meskipun required() di form
                        ])
                        ->columnSpanfull(), 

                    // Kolom Samping (Pengaturan & File)
                    Section::make('Pengaturan & File')
                        ->schema([
                            // FileUpload
                            FileUpload::make('file_path')
                                ->label('Upload File (Gambar/Video)')
                                ->helperText('Hanya menerima file Gambar (JPG, PNG, WEBP) atau Video (MP4, MOV). Maks. 10MB.')
                                ->disk('public') 
                                ->directory('general-materials')
                                ->visibility('public')
                                ->openable()
                                ->downloadable()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4', 'video/quicktime'])
                                ->maxSize(10240) // 10MB
                                ->columnSpanFull()
                                ->nullable(),
                            // Select untuk Status (Sesuai ENUM)
                            Select::make('status')
                                ->label('Status Publikasi')
                                ->helperText('Pilih status materi (Aktif = dapat dilihat, Diarsipkan = disembunyikan).')
                                ->options([
                                    'active' => 'Aktif (Dapat Dilihat)',
                                    'archived' => 'Diarsipkan (Tersembunyi)',
                                ])
                                ->default('active')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->columnSpanfull(),
            ]);
    }
}
