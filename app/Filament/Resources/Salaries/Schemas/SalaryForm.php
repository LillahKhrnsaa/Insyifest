<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Coach;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class SalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('coach_id')
                    ->label('Pelatih')
                    ->options(function () {
                        return Coach::with('user')
                            ->get()
                            ->mapWithKeys(fn ($coach) => [
                                $coach->id => $coach->user->name ?? "Coach #{$coach->id}"
                            ]);
                    })
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateHydrated(function ($state, Set $set) {
                    if ($state) {
                        $coach = Coach::withCount('members')->find($state);
                        $dbCount = $coach?->members_count ?? 0;
                        $set('original_member_count', $dbCount); 
                    }
                })
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            $coach = Coach::withCount('members')->find($state);
                            $memberCount = $coach?->members_count ?? 0;
                            
                            $set('original_member_count', $memberCount); 
                            
                            $currentAdditional = count($get('additional_athletes') ?? []);
                            $set('member_count', $memberCount + $currentAdditional);
                            
                            $set('total_amount', self::calculateTotal($get));
                        } else {
                            $set('original_member_count', 0);
                            $set('member_count', 0);
                            $set('total_amount', 0);
                        }
                    }),
                    
                TagsInput::make('additional_athletes')
                    ->label('Atlet Tambahan / Substitusi')
                    ->placeholder('Ketik nama atlet lalu tekan Enter')
                    ->helperText('Masukkan nama atlet di luar binaan coach ini (misal: atlet titipan/pengganti).')
                    ->reorderable()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $original = (int) $get('original_member_count');
                        $additional = count($state ?? []);
                        
                        $set('member_count', $original + $additional);
                        
                        $set('total_amount', self::calculateTotal($get));
                    }),

                Hidden::make('original_member_count')
                    ->default(0)
                    ->dehydrated(false),

                TextInput::make('member_count')
                    ->label('Jumlah Atlet')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->readOnly()
                    ->dehydrated(false)
                    ->helperText('Otomatis dihitung dari jumlah atlet yang ditugaskan')
                    ->formatStateUsing(function ($state, Get $get, $record) {
                    if (!$record) return $state;

                    $coachId = $get('coach_id');
                    $original = 0;
                    
                    if ($coachId) {
                        $coach = Coach::withCount('members')->find($coachId);
                        $original = $coach?->members_count ?? 0;
                    }

                    $additionalData = $get('additional_athletes');
                    $additional = is_array($additionalData) ? count($additionalData) : 0;
                    
                    return $original + $additional;
                }),
                    
                TextInput::make('training_sessions')
                    ->label('Jumlah Pertemuan')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('per_meeting_fee')
                    ->label('Nominal per Pertemuan')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('per_member_fee')
                    ->label('Nominal per Atlet')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('transport_fee')
                    ->label('Uang Transport')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('health_fee')
                    ->label('Uang Kesehatan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('bonus')
                    ->label('Bonus Tambahan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $memberCount = (float) ($get('member_count') ?? 0);
                        $set('total_amount', self::calculateTotal($get, $memberCount));
                    }),

                TextInput::make('total_amount')
                    ->label('Total Gaji')
                    ->prefix('Rp')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true)
                    ->default(0)
                    ->helperText('Dihitung otomatis berdasarkan komponen gaji'),

                TextInput::make('month')
                    ->label('Periode (Bulan)')
                    ->placeholder('Contoh: Oktober 2025')
                    ->required()
                    ->maxLength(50),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                    ])
                    ->default('pending')
                    ->required()
                    ->native(false)
                    ->live(),

                DatePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->native(false)
                    ->visible(fn (Get $get) => $get('status') === 'paid')
                    ->required(fn (Get $get) => $get('status') === 'paid'),
            ]);
    }

    protected static function calculateTotal(Get $get): float
    {
        // Ambil nilai dari form
        $trainingSessions = (float) ($get('training_sessions') ?? 0);
        $perMeetingFee = (float) ($get('per_meeting_fee') ?? 0);
        $perMemberFee = (float) ($get('per_member_fee') ?? 0);
        $transport = (float) ($get('transport_fee') ?? 0);
        $health = (float) ($get('health_fee') ?? 0);
        $bonus = (float) ($get('bonus') ?? 0);
        
        $members = (float) ($get('member_count') ?? 0);

        $meetingTotal = $trainingSessions * $perMeetingFee;
        $memberTotal = $members * $perMemberFee;
        
        return $transport + $meetingTotal + $memberTotal + $health + $bonus;
    }
}