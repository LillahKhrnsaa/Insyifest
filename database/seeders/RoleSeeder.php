<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'owner', 'display_name' => 'Owner'],
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'coach', 'display_name' => 'Coach'],
            ['name' => 'member', 'display_name' => 'Member'],
            ['name' => 'staff', 'display_name' => 'Staff'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => 'web'],
                ['display_name' => $role['display_name']]
            );
        }
    }
}
