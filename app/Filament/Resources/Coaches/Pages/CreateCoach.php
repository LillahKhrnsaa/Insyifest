<?php

namespace App\Filament\Resources\Coaches\Pages;

use App\Filament\Resources\Coaches\CoachResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateCoach extends CreateRecord
{
    protected static string $resource = CoachResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            DB::beginTransaction();

            // Siapkan data user
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'password' => Hash::make($data['password']),
                'photo_path' => $data['photo_path'] ?? null,
                'active' => $data['active'] ?? true,
                'email_verified_at' => $data['email_verified_at'] ?? now(),
            ];

            // Buat user baru
            $user = User::create($userData);
            
            // Sync role ke coach
            $user->syncRoles(['coach']);

            DB::commit();

            // Return data untuk coach record
            return [
                'user_id' => $user->id,
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

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Coach berhasil ditambahkan!';
    }
}
