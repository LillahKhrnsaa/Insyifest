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

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Foto Profil
                ImageColumn::make('photo_url') // pakai accessor, bukan langsung photo_path
                    ->label('Foto')
                    ->circular()
                    ->alignCenter()
                    ->size(40),

                // 2. Nama Lengkap
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user-circle')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(), // biar gak kepotong

                // 3. Email
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->iconColor('blue')
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->tooltip(fn ($state) => $state)
                    ->wrap(), // biar kalau panjang pecah baris

                // 4. Nomor Telepon
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

                // 5. Jenis Kelamin
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

                // 6. Tanggal Lahir
                TextColumn::make('birth_date')
                    ->label('Tgl. Lahir')
                    ->date('d M Y')
                    ->alignCenter()
                    ->sortable(),

                // 7. Status Akun
                ToggleColumn::make('active')
                    ->label('Aktif')
                    ->alignCenter()
                    ->tooltip('Klik untuk aktif/nonaktif')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->sortable(),

                // 8. Role Pengguna (pakai accessor dari model)
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->alignCenter()
                    ->colors([
                        'super admin' => 'danger',
                        'owner' => 'primary',
                        'admin' => 'blue',
                        'coach' => 'warning',
                        'staff' => 'success',
                        'member' => 'gray',
                    ])
                    ->sortable()
                    ->searchable(),

                // 9. Dibuat
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->alignCenter()
                    ->sortable(),

                // 10. Diupdate
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y, H:i')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                // Bisa tambahin filter aktif / role kalau perlu
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
                    
                    // 1. Schema yang disederhanakan
                    ->schema(function (User $record) {
                        // Ambil SEMUA permission yang ada, lalu kelompokkan
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
                                    CheckboxList::make("permissions.{$group}") // Gunakan nested key
                                        ->label(false)
                                        ->options($options)
                                        ->columns(2)
                                        // ✅ Default-nya hanya mengambil direct permissions milik user
                                        ->default(
                                            $record->permissions // <-- Jauh lebih sederhana
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
                    
                    // 2. Action yang disederhanakan
                    ->action(function (User $record, array $data): void {
                        // Ambil semua ID dari semua tab
                        $selectedIds = collect($data['permissions'] ?? [])
                            ->flatten()
                            ->filter()
                            ->map(fn ($id) => (int)$id)
                            ->unique()
                            ->toArray();

                        // ✅ Langsung sinkronkan permission yang dipilih ke user
                        $record->syncPermissions($selectedIds);

                        // Reset cache Spatie
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
                        ->modalHeading('Hapus Beberapa User')
                        ->modalDescription('Yakin ingin menghapus {count} pengguna yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
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
