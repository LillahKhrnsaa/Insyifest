<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CustomResource extends Command
{
    protected $signature = 'make:resource-custom {name}';
    protected $description = 'Creates a Filament v4 resource, policy, permissions, and registers policy in AuthServiceProvider.';

    public function handle()
    {
        $name = $this->argument('name');
        $modelName = Str::studly($name); // ex: Permission → Permission
        $tableName = Str::snake(Str::plural($name)); // ex: Permission → permissions
        $titleAttribute = 'name'; // default, bisa kamu ubah kalau modelnya beda

        /**
         * Step 1: Generate Filament Resource
         */
        $this->info("Creating Filament Resource for '{$modelName}'...");
        $cmd = "php artisan make:filament-resource {$modelName}";
        passthru($cmd, $exitCode);

        if ($exitCode !== 0) {
            $this->error("Failed to create resource.");
            return Command::FAILURE;
        }

        // Inject recordTitleAttribute
        $resourceFilePath = app_path("Filament/Resources/{$modelName}Resource.php");
        if (File::exists($resourceFilePath)) {
            $content = File::get($resourceFilePath);
            if (!str_contains($content, '$recordTitleAttribute')) {
                $content = str_replace(
                    "protected static ?string \$model = null;",
                    "protected static ?string \$model = \App\Models\\{$modelName}::class;\n    protected static ?string \$recordTitleAttribute = '{$titleAttribute}';",
                    $content
                );
                File::put($resourceFilePath, $content);
                $this->info("Added recordTitleAttribute='{$titleAttribute}' to {$modelName}Resource.");
            }
        }

        /**
         * Step 2: Generate Policy
         */
        $this->info("Creating Policy for '{$modelName}'...");
        $cmd = "php artisan make:policy {$modelName}Policy --model={$modelName}";
        passthru($cmd, $exitCode);

        if ($exitCode !== 0) {
            $this->error("Failed to create policy.");
            return Command::FAILURE;
        }

        // Inject permission-based checks ke Policy
        $policyFilePath = app_path("Policies/{$modelName}Policy.php");
        if (File::exists($policyFilePath)) {
            $policyContent = File::get($policyFilePath);

            $permissions = [
                'viewAny' => "viewAny.{$tableName}",
                'view'    => "view.{$tableName}",
                'create'  => "create.{$tableName}",
                'update'  => "update.{$tableName}",
                'delete'  => "delete.{$tableName}",
            ];

            foreach ($permissions as $method => $permissionName) {
                $modelArgument = '';
                $canCallArgument = '';

                if (!in_array($method, ['viewAny', 'create'])) {
                    $modelVarName = Str::camel($modelName);
                    $modelArgument = ", {$modelName} \${$modelVarName}";
                    $canCallArgument = ", \${$modelVarName}";
                }

                $newFunction = "public function {$method}(User \$user{$modelArgument}): bool\n    {\n        return \$user->can('{$permissionName}'{$canCallArgument});\n    }";

                $pattern = "/public function {$method}\([^)]*\)\s*:\s*bool\s*\{[^}]*\}/";
                $policyContent = preg_replace($pattern, $newFunction, $policyContent);
            }

            File::put($policyFilePath, $policyContent);
            $this->info("Policy for '{$modelName}' updated with permission checks.");
        }

        /**
         * Step 3: Create Permissions
         */
        $this->info("Creating specific permissions...");
        $permissionNames = [
            "viewAny.{$tableName}",
            "view.{$tableName}",
            "create.{$tableName}",
            "update.{$tableName}",
            "delete.{$tableName}",
        ];

        foreach ($permissionNames as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        /**
         * Step 4: Assign to super-admin
         */
        $this->info("Assigning permissions to 'staff' role...");
        $superAdminRole = Role::firstOrCreate(['name' => 'staff']);
        $newPermissions = Permission::whereIn('name', $permissionNames)->get();
        $superAdminRole->givePermissionTo($newPermissions);
        $this->info("Permissions assigned to staff ✅");

        /**
         * Step 5: Register Policy in AuthServiceProvider
         */
        $this->info("Registering policy in AuthServiceProvider...");
        $authProvider = app_path("Providers/AuthServiceProvider.php");

        if (File::exists($authProvider)) {
            $authContent = File::get($authProvider);

            // Tambahin use statement kalau belum ada
            if (!str_contains($authContent, "use App\\Models\\{$modelName};")) {
                $authContent = preg_replace(
                    "/namespace App\\\\Providers;\n/",
                    "namespace App\Providers;\n\nuse App\Models\\{$modelName};\nuse App\Policies\\{$modelName}Policy;\n",
                    $authContent
                );
            }

            // Tambahin ke protected $policies
            if (!str_contains($authContent, "{$modelName}::class => {$modelName}Policy::class")) {
                $authContent = preg_replace(
                    "/protected \$policies = \[/",
                    "protected \$policies = [\n        {$modelName}::class => {$modelName}Policy::class,",
                    $authContent
                );
            }

            File::put($authProvider, $authContent);
            $this->info("Policy for '{$modelName}' registered in AuthServiceProvider ✅");
        } else {
            $this->warn("AuthServiceProvider.php not found, please check manually.");
        }

        $this->info("🎉 All done! {$modelName} resource, policy, permissions, and Auth integration created.");
        return Command::SUCCESS;
    }
}
