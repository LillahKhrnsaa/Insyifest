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
            ['name' => 'Luthfi', 'email' => 'owner@cikampekswimming.gmail.com', 'phone' => '0811111111', 'gender' => 'MALE', 'role' => 'owner'],
            ['name' => 'Adinda', 'email' => 'admin@cikampekswimming.gmail.com', 'phone' => '0822222222', 'gender' => 'FEMALE', 'role' => 'admin'],
            ['name' => 'Alif', 'email' => 'coach@cikampekswimming.gmail.com','phone' => '0833333333', 'gender' => 'MALE', 'role' => 'coach'],
            ['name' => 'IT Team', 'email' => 'it@cikampekswimming.gmail.com', 'phone' => '0855555555', 'gender' => 'MALE', 'role' => 'staff'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' =>$data['email'],
                    'gender' => $data['gender'],
                    'password' => Hash::make('1234567890'),
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
