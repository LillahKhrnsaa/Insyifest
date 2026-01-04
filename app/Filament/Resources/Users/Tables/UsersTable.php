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
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Storage;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->circular()
                    ->alignCenter()
                    ->imageHeight(40)
                    ->width(40)
                    ->disk('public'),

                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('blue')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->copyable()
                    ->copyMessage('Nomor telepon disalin!')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->alignCenter()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'MALE' => 'blue',
                        'FEMALE' => 'pink',
                        default => 'gray',
                    }),

                TextColumn::make('birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->alignCenter()
                    ->sortable(),

                ToggleColumn::make('active')
                    ->label('Aktif')
                    ->alignCenter()
                    ->tooltip('Klik untuk aktif/nonaktif')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->alignCenter()
                    ->color(fn (string $state): string => match ($state) {
                        'super admin' => 'danger',
                        'owner' => 'primary',
                        'admin' => 'info',
                        'coach' => 'warning',
                        'staff' => 'success',
                        'member' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => Str::ucfirst($state))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                // Bisa tambahin filter aktif / role kalau perlu
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('managePermissions')
                        ->label('Atur Permissions')
                        ->tooltip('Manage Direct Permissions')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->visible(fn () => Auth::user()?->hasRole('staff'))
                        ->modalHeading('Manage Direct Permissions')
                        ->modalSubmitActionLabel('Simpan')
                        ->modalCancelActionLabel('Batal')
                        ->extraAttributes([
                            'class' => 'border border-yellow-300 text-yellow-700 bg-white hover:bg-yellow-50 rounded-lg px-3 py-2',
                        ])
                        
                        // 1. Schema yang disederhanakan
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
    
                                $tabs[] = Tab::make(Str::ucfirst($group))
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
                        ->label('Lihat')
                        ->tooltip('View details')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->size('sm')
                        ->extraAttributes([
                            'class' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg px-3 py-2']),
    
                    EditAction::make()
                        ->label('Edit')
                        ->tooltip('Edit role')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->size('sm')
                        ->extraAttributes([
                            'class' => 'border border-blue-300 text-blue-700 bg-white hover:bg-blue-50 rounded-lg px-3 py-2']),
    
                    DeleteAction::make()
                        ->label('Delete')
                        ->tooltip('Delete role')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->size('sm')
                        ->requiresConfirmation()
                        ->extraAttributes([
                            'class' => 'border border-red-300 text-red-700 bg-white hover:bg-red-50 rounded-lg px-3 py-2']),
                    ])
                    ->icon('heroicon-o-bars-4')
                    ->label('')
                    ->button()
                ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Pilihan')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Beberapa User')
                        ->modalDescription('Yakin ingin menghapus {count} pengguna yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
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
