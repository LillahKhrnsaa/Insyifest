<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Coach;
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
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state) {
                            // Ambil jumlah member dari pivot table member_training_assignments
                            $coach = Coach::withCount('members')->find($state);
                            $memberCount = $coach?->members_count ?? 0;
                            $set('member_count', $memberCount);
                            
                            // Trigger recalculation total
                            $set('total_amount', self::calculateTotal($get, $memberCount));
                        } else {
                            $set('member_count', 0);
                            $set('total_amount', 0);
                        }
                    }),

                // 👥 Jumlah Member (otomatis dari pivot)
                TextInput::make('member_count')
                    ->label('Jumlah Atlet')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false) // Virtual field, tidak disimpan ke DB
                    ->helperText('Otomatis dihitung dari jumlah atlet yang ditugaskan'),
                    
                // 🏋️ Jumlah Pertemuan
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

                // 💵 Nominal per Pertemuan
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

                // 💰 Nominal per Atlet
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

                // 💸 Uang Transport
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

                // ❤️ Uang Kesehatan
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

                // 🎁 Bonus Tambahan
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

                // 🧮 Total Otomatis
                TextInput::make('total_amount')
                    ->label('Total Gaji')
                    ->prefix('Rp')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true)
                    ->default(0)
                    ->helperText('Dihitung otomatis berdasarkan komponen gaji'),

                // 🗓 Bulan Gaji
                TextInput::make('month')
                    ->label('Periode (Bulan)')
                    ->placeholder('Contoh: Oktober 2025')
                    ->required()
                    ->maxLength(50),

                // 📋 Status Pembayaran
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

                // 📅 Tanggal Dibayar
                DatePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->native(false)
                    ->visible(fn (Get $get) => $get('status') === 'paid')
                    ->required(fn (Get $get) => $get('status') === 'paid'),
            ]);
    }

    /**
     * 🔢 Fungsi menghitung total gaji
     * 
     * Formula:
     * Total = Transport + (Pertemuan × Nominal/Pertemuan) + (Atlet × Nominal/Atlet) + Kesehatan + Bonus
     */
    protected static function calculateTotal(Get $get, ?float $memberCount = null): float
    {
        // Ambil nilai dari form
        $trainingSessions = (float) ($get('training_sessions') ?? 0);
        $perMeetingFee = (float) ($get('per_meeting_fee') ?? 0);
        $perMemberFee = (float) ($get('per_member_fee') ?? 0);
        $transport = (float) ($get('transport_fee') ?? 0);
        $health = (float) ($get('health_fee') ?? 0);
        $bonus = (float) ($get('bonus') ?? 0);
        
        // Gunakan member_count yang dikirim atau ambil dari form
        $members = $memberCount ?? (float) ($get('member_count') ?? 0);

        // Hitung komponen gaji
        $meetingTotal = $trainingSessions * $perMeetingFee;  // Hasil uang pertemuan
        $memberTotal = $members * $perMemberFee;             // Hasil uang atlet
        
        // Total = Transport + Pertemuan + Atlet + Kesehatan + Bonus
        return $transport + $meetingTotal + $memberTotal + $health + $bonus;
    }
}