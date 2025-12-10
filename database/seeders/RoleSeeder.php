<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles for admin guard (using firstOrCreate to avoid duplicates)
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'admin']
        );

        $panelUser = Role::firstOrCreate(
            ['name' => 'panel_user', 'guard_name' => 'admin']
        );

        $editor = Role::firstOrCreate(
            ['name' => 'editor', 'guard_name' => 'admin']
        );

        // Assign super_admin role to Moataz
        $moataz = Admin::where('email', 'moataz@businessbelarabia.com')->first();
        if ($moataz && !$moataz->hasRole('super_admin')) {
            $moataz->assignRole('super_admin');
        }

        // Assign editor role to the other admin
        $admin = Admin::where('email', 'admin@businessbelarabia.com')->first();
        if ($admin && !$admin->hasRole('editor')) {
            $admin->assignRole('editor');
        }

        $this->command->info('Roles created/updated and assigned successfully!');
        $this->command->info('Super Admin: moataz@businessbelarabia.com');
        $this->command->info('Editor: admin@businessbelarabia.com');
    }
}
