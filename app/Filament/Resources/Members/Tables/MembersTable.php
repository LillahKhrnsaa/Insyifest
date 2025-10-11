<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Foto Profil
                ImageColumn::make('user.photo_path')->label('Foto')
                    ->circular()->imageHeight(40)->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user->name ?? 'M')),

                // 2. Nama, Email, & Telepon
                TextColumn::make('user.name')->label('Nama Member')
                    ->searchable()->sortable()->weight('bold')->color('primary')
                    ->description(fn ($record) => $record->user->email) // Tampilkan email di bawah nama
                    ->tooltip(fn ($record) => "Telepon: " . ($record->user->phone ?? '-')),

                // 3. Paket Latihan
                TextColumn::make('trainingPackage.name')->label('Paket Latihan')
                    ->badge()->color('success')->default('Belum ada paket')->searchable(),

                // 4. Status Akun (User Active/Inactive) - Toggle Interaktif
                ToggleColumn::make('user.active')->label('Akun Aktif')
                    ->tooltip('Klik untuk mengaktifkan/menonaktifkan akun user')
                    ->onColor('success')->offColor('danger'),

                // 5. Status Keanggotaan (Member AKTIF/TIDAK_AKTIF) - Select Interaktif
                SelectColumn::make('status')->label('Status Member')
                    ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif'])
                    ->sortable(),
                
                // 6. Dibuat Pada (Toggleable)
                TextColumn::make('created_at')->label('Dibuat Pada')
                    ->dateTime('d M Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan status keanggotaan
                SelectFilter::make('status')->label('Status Member')
                    ->options(['AKTIF' => 'Aktif', 'TIDAK_AKTIF' => 'Tidak Aktif']),
                
                // Filter berdasarkan paket latihan
                SelectFilter::make('training_package_id')->label('Paket Latihan')
                    ->relationship('trainingPackage', 'name')->searchable()->preload(),

                // Filter canggih berdasarkan status akun user
                SelectFilter::make('active')->label('Status Akun')
                    ->options([1 => 'Aktif', 0 => 'Nonaktif'])
                    ->query(fn (Builder $query, array $data) =>
                        $data['value'] !== null ? $query->whereHas('user', fn ($q) => $q->where('active', $data['value'])) : null
                    ),
            ])
            // ✅ SINTAKS BENAR: Menggunakan ->actions()
            ->recordActions([
                ViewAction::make()->label('')->button()->tooltip('Lihat detail')->icon('heroicon-o-eye'),
                EditAction::make()->label('')->button()->tooltip('Edit Member')->icon('heroicon-o-pencil-square'),
                DeleteAction::make()->label('')->button()->tooltip('Hapus Member')
                    ->icon('heroicon-o-trash')->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Member')
                    ->modalDescription('Yakin ingin menghapus member ini? Data user terkait juga akan terhapus!')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    // HOOK PENTING: Hapus user terkait sebelum menghapus member
                    ->before(fn ($record) => $record->user?->delete()), 
            ])
            // ✅ SINTAKS BENAR: Menggunakan ->bulkActions()
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Pilihan')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Member Terpilih')
                        ->modalDescription('Yakin ingin menghapus semua member yang dipilih? Data user terkait juga akan terhapus!')
                        // HOOK PENTING: Hapus semua user terkait
                        ->before(function ($records) {
                            $userIds = $records->pluck('user_id')->filter();
                            if ($userIds->isNotEmpty()) {
                                \App\Models\User::whereIn('id', $userIds)->delete();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
