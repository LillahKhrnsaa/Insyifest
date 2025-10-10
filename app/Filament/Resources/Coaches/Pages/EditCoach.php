<?php

namespace App\Filament\Resources\Coaches\Pages;

use App\Filament\Resources\Coaches\CoachResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditCoach extends EditRecord
{
    protected static string $resource = CoachResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    // Hapus user terkait juga
                    $record->user?->delete();
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load data user ke form
        $user = $this->record->user;
        
        if ($user) {
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['phone'] = $user->phone;
            $data['birth_date'] = $user->birth_date;
            $data['gender'] = $user->gender;
            $data['photo_path'] = $user->photo_path;
            $data['active'] = $user->active;
            $data['role'] = 'coach';
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->record->user;
        
        // 🚨 VALIDASI MANUAL EMAIL UNIK (Sudah ada)
        if ($user && $data['email'] !== $user->email) {
            $this->validate([
                'data.email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id, 'id'), 
                ],
            ], [
                'data.email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            ]);
        }
        
        // 🚨 LANGKAH BARU: VALIDASI MANUAL NOMOR TELEPON UNIK
        if ($user && $data['phone'] !== $user->phone) {
            $this->validate([
                'data.phone' => [
                    'required',
                    'tel',
                    // Gunakan Rule::unique, abaikan $user->id di kolom 'id' tabel 'users'
                    Rule::unique('users', 'phone')->ignore($user->id, 'id'), 
                ],
            ], [
                'data.phone.unique' => 'Nomor telepon ini sudah digunakan oleh akun lain.',
            ]);
        }
        
        // Logika lo untuk memproses data tetap dipertahankan
        try {
            DB::beginTransaction();
            
            if ($user) {
                // Siapkan data user untuk update
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'], // Data yang sudah tervalidasi
                    'birth_date' => $data['birth_date'],
                    'gender' => $data['gender'],
                    'photo_path' => $data['photo_path'] ?? null,
                    'active' => $data['active'] ?? true,
                ];

                // Kalo password diisi, hash dan update
                if (!empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }

                // Update user
                $user->update($userData);
                
                // Pastikan role tetap coach
                $user->syncRoles(['coach']);
            }

            DB::commit();

            // Return data coach untuk update
            return [
                'bio' => $data['bio'] ?? null,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data coach berhasil diupdate!';
    }
}
