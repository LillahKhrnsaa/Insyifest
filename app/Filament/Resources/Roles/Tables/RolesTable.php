<?php

namespace App\Filament\Resources\Roles\Tables;

use App\Models\Permission;
use Filament\Actions\BulkActionGroup;
use Spatie\Permission\PermissionRegistrar;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Facades\Auth;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Role')
                ->searchable()
                ->sortable()
                ->color('primary')
                ->weight('font-bold')
                ->icon('heroicon-o-code-bracket'),

            TextColumn::make('display_name')
                ->label('Nama Role')
                ->searchable()
                ->sortable()
                ->color('gray-800')
                ->icon('heroicon-o-user-circle'),

            TextColumn::make('guard_name')
                ->label('Guard')
                ->searchable()
                ->badge()
                ->colors(['web' => 'gray']),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y')
                ->sortable(),

            TextColumn::make('updated_at')
                ->label('Diupdate')
                ->dateTime('d M Y')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('managePermissions')
                    ->label('')
                    ->tooltip('Manage Permissions')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->size('sm')
                    ->visible(fn () => Auth::user()?->hasRole('staff'))
                    ->button()
                    ->schema(function ($record) {
                        // ambil semua permission lalu group by suffix (misal "role")
                        $permissions = Permission::all()
                            ->groupBy(function ($perm) {
                                return explode('.', $perm->name)[1] ?? $perm->name;
                            });

                        $tabs = [];

                        foreach ($permissions as $group => $perms) {
                            // opsi = prefix (misal "create", "update", "delete")
                            $options = $perms->mapWithKeys(function ($perm) {
                                $parts = explode('.', $perm->name);
                                $prefix = $parts[0] ?? $perm->name;
                                return [$perm->id => ucfirst($prefix)];
                            })->toArray();

                            $tabs[] = Tab::make(ucfirst($group))
                                ->schema([
                                    CheckboxList::make("permissions.$group")
                                        ->label(false)
                                        ->options($options)
                                        ->columns(2)
                                        ->default(
                                            $record->permissions
                                                ->whereIn('id', $perms->pluck('id'))
                                                ->pluck('id')
                                                ->toArray()
                                        ),
                                ]);
                        }

                        return [
                            Tabs::make('Permissions')->tabs($tabs),
                        ];
                    })
                    ->action(function (array $data, $record) {
                        // kumpulkan semua permission_id dari tiap tab
                        $selectedIds = collect($data['permissions'] ?? [])
                            ->flatten()
                            ->filter()
                            ->toArray();

                        $record->permissions()->sync($selectedIds);

                        // reset cache
                        app()[PermissionRegistrar::class]->forgetCachedPermissions();
                    })
                    ->modalHeading('Manage Permissions')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->extraAttributes([
                        'class' => 'border border-yellow-300 text-yellow-700 bg-white hover:bg-yellow-50 rounded-lg px-3 py-2',
                    ]),

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
                        ->label('')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Multiple Role')
                        ->modalDescription('Yakin ingin menghapus {count} role yang dipilih?')
                        ->modalSubmitActionLabel('Hapus Semua')
                        ->modalCancelActionLabel('Batal'),
                ])
                ->dropdownWidth('w-48')
                ->button() // Tampilkan sebagai button, bukan dropdown
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->size('sm'),
            ]);
    }
}
