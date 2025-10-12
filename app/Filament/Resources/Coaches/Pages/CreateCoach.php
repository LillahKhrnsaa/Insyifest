<?php

namespace App\Filament\Resources\Coaches\Pages;

use App\Filament\Resources\Coaches\CoachResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coach;

class CreateCoach extends CreateRecord
{
    protected static string $resource = CoachResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Create User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
            'photo_path' => $data['photo_path'] ?? null,
            'active' => $data['active'] ?? true,
            'email_verified_at' => $data['email_verified_at'] ?? now(),
        ]);

        $user->syncRoles(['coach']);

        // 2. Create Coach dengan user_id
        $coach = Coach::create([
            'user_id' => $user->id,
            'bio' => $data['bio'] ?? null,
        ]);

        return $coach;
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
