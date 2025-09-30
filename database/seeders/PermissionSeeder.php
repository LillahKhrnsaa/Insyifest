<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // ==== ROLES ====
            ['name' => 'viewAny.roles', 'guard_name' => 'web', 'description' => 'Akses ViewAny Roles', 'display_name' => 'ViewAny Roles'],
            ['name' => 'view.roles',    'guard_name' => 'web', 'description' => 'Akses View Roles',   'display_name' => 'View Roles'],
            ['name' => 'create.roles',  'guard_name' => 'web', 'description' => 'Akses Create Roles', 'display_name' => 'Create Roles'],
            ['name' => 'update.roles',  'guard_name' => 'web', 'description' => 'Akses Update Roles', 'display_name' => 'Update Roles'],
            ['name' => 'delete.roles',  'guard_name' => 'web', 'description' => 'Akses Delete Roles', 'display_name' => 'Delete Roles'],

        ];

        $now = Carbon::now();

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                [
                    'guard_name' => $perm['guard_name'],
                    'description' => $perm['description'],
                    'display_name' => $perm['display_name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['created_at' => $now, 'updated_at' => $now]
        );
        $superAdmin->syncPermissions(Permission::all());
    }
}
