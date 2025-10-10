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
            ['name' => 'view.roles',    'guard_name' => 'web', 'description' => 'Akses View Roles',    'display_name' => 'View Roles'],
            ['name' => 'create.roles',  'guard_name' => 'web', 'description' => 'Akses Create Roles',  'display_name' => 'Create Roles'],
            ['name' => 'update.roles',  'guard_name' => 'web', 'description' => 'Akses Update Roles',  'display_name' => 'Update Roles'],
            ['name' => 'delete.roles',  'guard_name' => 'web', 'description' => 'Akses Delete Roles',  'display_name' => 'Delete Roles'],

            // ==== PERMISSIONS ====
            ['name' => 'viewAny.permissions', 'guard_name' => 'web', 'description' => 'Akses ViewAny Permissions', 'display_name' => 'ViewAny Permissions'],
            ['name' => 'view.permissions',    'guard_name' => 'web', 'description' => 'Akses View Permissions',    'display_name' => 'View Permissions'],
            ['name' => 'create.permissions',  'guard_name' => 'web', 'description' => 'Akses Create Permissions',  'display_name' => 'Create Permissions'],
            ['name' => 'update.permissions',  'guard_name' => 'web', 'description' => 'Akses Update Permissions',  'display_name' => 'Update Permissions'],
            ['name' => 'delete.permissions',  'guard_name' => 'web', 'description' => 'Akses Delete Permissions',  'display_name' => 'Delete Permissions'],

            // ==== USERS ====
            ['name' => 'viewAny.users', 'guard_name' => 'web', 'description' => 'Akses ViewAny Users', 'display_name' => 'ViewAny Users'],
            ['name' => 'view.users',    'guard_name' => 'web', 'description' => 'Akses View Users',    'display_name' => 'View Users'],
            ['name' => 'create.users',  'guard_name' => 'web', 'description' => 'Akses Create Users',  'display_name' => 'Create Users'],
            ['name' => 'update.users',  'guard_name' => 'web', 'description' => 'Akses Update Users',  'display_name' => 'Update Users'],
            ['name' => 'delete.users',  'guard_name' => 'web', 'description' => 'Akses Delete Users',  'display_name' => 'Delete Users'],

            // ==== BANK ACCOUNTS ====
            ['name' => 'viewAny.bank_accounts', 'guard_name' => 'web', 'description' => 'Akses ViewAny Bank Accounts', 'display_name' => 'ViewAny Bank Accounts'],
            ['name' => 'view.bank_accounts',    'guard_name' => 'web', 'description' => 'Akses View Bank Accounts',    'display_name' => 'View Bank Accounts'],
            ['name' => 'create.bank_accounts',  'guard_name' => 'web', 'description' => 'Akses Create Bank Accounts',  'display_name' => 'Create Bank Accounts'],
            ['name' => 'update.bank_accounts',  'guard_name' => 'web', 'description' => 'Akses Update Bank Accounts',  'display_name' => 'Update Bank Accounts'],
            ['name' => 'delete.bank_accounts',  'guard_name' => 'web', 'description' => 'Akses Delete Bank Accounts',  'display_name' => 'Delete Bank Accounts'],

            // ==== TRAINING PACKAGES ====
            ['name' => 'viewAny.training_packages', 'guard_name' => 'web', 'description' => 'Akses Viewany Training Packages', 'display_name' => 'Viewany Training Packages'],
            ['name' => 'view.training_packages',    'guard_name' => 'web', 'description' => 'Akses View Training Packages',    'display_name' => 'View Training Packages'],
            ['name' => 'create.training_packages',  'guard_name' => 'web', 'description' => 'Akses Create Training Packages',  'display_name' => 'Create Training Packages'],
            ['name' => 'update.training_packages',  'guard_name' => 'web', 'description' => 'Akses Update Training Packages',  'display_name' => 'Update Training Packages'],
            ['name' => 'delete.training_packages',  'guard_name' => 'web', 'description' => 'Akses Delete Training Packages',  'display_name' => 'Delete Training Packages'],

            // ==== GENERAL MATERIALS ====
            ['name' => 'viewAny.general_materials', 'guard_name' => 'web', 'description' => 'Akses Viewany General Materials', 'display_name' => 'Viewany General Materials'],
            ['name' => 'view.general_materials',    'guard_name' => 'web', 'description' => 'Akses View General Materials',    'display_name' => 'View General Materials'],
            ['name' => 'create.general_materials',  'guard_name' => 'web', 'description' => 'Akses Create General Materials',  'display_name' => 'Create General Materials'],
            ['name' => 'update.general_materials',  'guard_name' => 'web', 'description' => 'Akses Update General Materials',  'display_name' => 'Update General Materials'],
            ['name' => 'delete.general_materials',  'guard_name' => 'web', 'description' => 'Akses Delete General Materials',  'display_name' => 'Delete General Materials'],
            
            // ==== FORM EKSTERNALS ====
            ['name' => 'viewAny.form_eksternals', 'guard_name' => 'web', 'description' => 'Akses Viewany Form Eksternals', 'display_name' => 'Viewany Form Eksternals'], 
            ['name' => 'view.form_eksternals', 'guard_name' => 'web', 'description' => 'Akses View Form Eksternals', 'display_name' => 'View Form Eksternals'], 
            ['name' => 'create.form_eksternals', 'guard_name' => 'web', 'description' => 'Akses Create Form Eksternals', 'display_name' => 'Create Form Eksternals'], 
            ['name' => 'update.form_eksternals', 'guard_name' => 'web', 'description' => 'Akses Update Form Eksternals', 'display_name' => 'Update Form Eksternals'], 
            ['name' => 'delete.form_eksternals', 'guard_name' => 'web', 'description' => 'Akses Delete Form Eksternals', 'display_name' => 'Delete Form Eksternals'],

            // ==== COACHES ====
            ['name' => 'viewAny.coaches', 'guard_name' => 'web', 'description' => 'Akses Viewany Coaches', 'display_name' => 'Viewany Coaches'],
            ['name' => 'view.coaches', 'guard_name' => 'web', 'description' => 'Akses View Coaches', 'display_name' => 'View Coaches'],
            ['name' => 'create.coaches', 'guard_name' => 'web', 'description' => 'Akses Create Coaches', 'display_name' => 'Create Coaches'],
            ['name' => 'update.coaches', 'guard_name' => 'web', 'description' => 'Akses Update Coaches', 'display_name' => 'Update Coaches'],
            ['name' => 'delete.coaches', 'guard_name' => 'web', 'description' => 'Akses Delete Coaches', 'display_name' => 'Delete Coaches'],

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

        // Assign semua permission ke role "staff"
        $staffRole = Role::firstOrCreate(
            ['name' => 'staff', 'guard_name' => 'web'],
            ['created_at' => $now, 'updated_at' => $now]
        );

        $staffRole->syncPermissions(Permission::all());
    }
}
