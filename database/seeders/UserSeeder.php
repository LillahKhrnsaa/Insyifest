<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Luthfi', 'phone' => '0811111111', 'gender' => 'MALE', 'role' => 'owner'],
            ['name' => 'Adinda', 'phone' => '0822222222', 'gender' => 'FEMALE', 'role' => 'admin'],
            ['name' => 'Alif', 'phone' => '0833333333', 'gender' => 'MALE', 'role' => 'coach'],
            ['name' => 'Reihan', 'phone' => '0844444444', 'gender' => 'FEMALE', 'role' => 'member'],
            ['name' => 'IT Team', 'phone' => '0855555555', 'gender' => 'MALE', 'role' => 'staff'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'gender' => $data['gender'],
                    'password' => Hash::make('1234567890'),
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
