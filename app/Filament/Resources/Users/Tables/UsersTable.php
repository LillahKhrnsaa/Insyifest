<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Permission;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Spatie\Permission\PermissionRegistrar;
use Filament\Forms\Components\CheckboxList;
use Filament\Actions\DeleteAction;
class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nama Lengkap (dari kolom 'name')
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('font-bold')
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip('Nama lengkap pengguna')
                    ->limit(20),

                // 2. Email
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->color('gray-700')
                    ->icon('heroicon-o-envelope')
                    ->iconColor('blue-500')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->limit(25)
                    ->toggleable(),

                // 3. Nomor Telepon (BARU)
                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 4. Jenis Kelamin (BARU)
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'MALE' => 'blue',
                        'FEMALE' => 'pink',
                        default => 'gray',
                    })
                    ->toggleable(),

                // 5. Tanggal Lahir (BARU)
                TextColumn::make('birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d F Y')
                    ->sortable()
                    ->icon('heroicon-o-cake')
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 6. Status Akun (dari kolom 'active')
                ToggleColumn::make('active')
                    ->label('Akun Aktif')
                    ->tooltip('Klik untuk mengaktifkan/menonaktifkan akun')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->sortable(),

                // 7. Role Pengguna
                TextColumn::make('roles.display_name')
                    ->label('Role Pengguna')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'Super Admin' => 'danger',
                        'Owner' => 'primary',
                        'Admin' => 'blue',
                        'Coach' => 'warning',
                        'Staff' => 'success',
                        'Member' => 'gray',
                    ])
                    ->toggleable(),

                // 8. Dibuat
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 9. Diupdate
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->icon('heroicon-o-arrow-path')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('managePermissions')
                    ->label('')
                    ->tooltip('Manage Direct Permissions')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->visible(fn () => Auth::user()?->hasRole('staff'))
                    ->button()
                    ->modalHeading('Manage Direct Permissions')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->extraAttributes([
                        'class' => 'border border-yellow-300 text-yellow-700 bg-white hover:bg-yellow-50 rounded-lg px-3 py-2',
                    ])

                    // Schema untuk Modal Permissions
                    ->schema(function (User $record) {
                        $permissions = Permission::query()->get()->groupBy(function ($permission) {
                            return explode('.', $permission->name)[1] ?? 'Lainnya';
                        });

                        $tabs = [];
                        foreach ($permissions as $group => $perms) {
                            $options = $perms->mapWithKeys(function ($perm) {
                                $prefix = explode('.', $perm->name)[0] ?? $perm->name;
                                return [$perm->id => Str::of($prefix)->replace('_', ' ')->ucfirst()];
                            })->toArray();

                            $tabs[] = Tabs\Tab::make(Str::ucfirst($group))
                                ->schema([
                                    CheckboxList::make("permissions.{$group}")
                                        ->label(false)
                                        ->options($options)
                                        ->columns(2)
                                        ->default(
                                            $record->permissions
                                                ->whereIn('id', $perms->pluck('id'))
                                                ->pluck('id')
                                                ->toArray()
                                        )
                                        ->rules(['nullable', 'array']),
                                ]);
                        }

                        return [
                            Tabs::make('Permissions')->tabs($tabs)->columnSpanFull(),
                        ];
                    })

                    // Action saat disubmit
                    ->action(function (User $record, array $data): void {
                        $selectedIds = collect($data['permissions'] ?? [])
                            ->flatten()
                            ->filter()
                            ->map(fn ($id) => (int)$id)
                            ->unique()
                            ->toArray();

                        $record->syncPermissions($selectedIds);

                        app(PermissionRegistrar::class)->forgetCachedPermissions();
                    }),

                ViewAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('View details')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),

                EditAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Edit role')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->size('sm')
                    ->extraAttributes([
                        'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),

                DeleteAction::make()
                    ->label('')
                    ->button()
                    ->tooltip('Delete role')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->extraAttributes([
                        'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2']),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Pilihan')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Multiple User')
                        ->modalDescription('Yakin ingin menghapus {count} pengguna yang dipilih?')
                        ->modalSubmitActionLabel('Hapus Semua')
                        ->modalCancelActionLabel('Batal'),
                ])
                ->dropdownWidth('w-48')
                ->button()
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->size('sm'),
            ]);
    }
}