<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            DB::beginTransaction();

            // 1. Ambil semua data yang relevan untuk tabel 'users'
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'password' => Hash::make($data['password']),
                'photo_path' => $data['photo_path'] ?? null,
                'active' => $data['active'] ?? true,
            ];

            // 2. Buat record User baru
            $user = User::create($userData);

            // 3. Set rolenya menjadi 'member'
            $user->syncRoles($data['role']);

            DB::commit();

            // 4. Return HANYA data yang relevan untuk tabel 'members'
            //    dan tambahkan user_id yang baru saja dibuat.
            return [
                'user_id' => $user->id,
                'training_package_id' => $data['training_package_id'],
                'status' => $data['status'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            // Optional: Log the error or show a notification
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Member baru berhasil ditambahkan!';
    }
}
