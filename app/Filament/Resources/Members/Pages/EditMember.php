<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(fn ($record) => $record->user?->delete()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil data dari relasi 'user' untuk mengisi form
        $user = $this->record->user;
        
        if ($user) {
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['phone'] = $user->phone;
            $data['birth_date'] = $user->birth_date;
            $data['gender'] = $user->gender;
            $data['photo_path'] = $user->photo_path;
            $data['active'] = $user->active;
            $data['role'] = 'member';
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->record->user;
        
        // VALIDASI MANUAL EMAIL UNIK (mengabaikan user saat ini)
        if ($user && $data['email'] !== $user->email) {
            $this->validate([
                'data.email' => [
                    'required', 'email',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
            ], ['data.email.unique' => 'Alamat email ini sudah digunakan.']);
        }
        
        // VALIDASI MANUAL NOMOR TELEPON UNIK (mengabaikan user saat ini)
        if ($user && $data['phone'] !== $user->phone) {
            $this->validate([
                'data.phone' => [
                    'required',
                    Rule::unique('users', 'phone')->ignore($user->id),
                ],
            ], ['data.phone.unique' => 'Nomor telepon ini sudah digunakan.']);
        }
        
        try {
            DB::beginTransaction();
            
            if ($user) {
                // Siapkan data untuk diupdate ke tabel 'users'
                $userData = [
                    'name'       => $data['name'],
                    'email'      => $data['email'],
                    'phone'      => $data['phone'],
                    'birth_date' => $data['birth_date'],
                    'gender'     => $data['gender'],
                    'photo_path' => $data['photo_path'] ?? null,
                    'active'     => $data['active'] ?? true,
                ];

                // Jika password baru diisi, hash dan update
                if (!empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }

                // Update data di tabel 'users'
                $user->update($userData);
                
                // Pastikan rolenya tetap 'member'
                $user->syncRoles(['member']);
            }

            DB::commit();

            // Return HANYA data yang relevan untuk tabel 'members'
            return [
                'training_package_id' => $data['training_package_id'],
                'status'              => $data['status'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
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
        return 'Data member berhasil diupdate!';
    }
}