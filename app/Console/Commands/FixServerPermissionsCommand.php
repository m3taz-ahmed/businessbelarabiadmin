<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;
use BezhanSalleh\FilamentShield\Support\Utils;

class FixServerPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-server-permissions {--assign-users : Assign roles to existing users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix server permissions for Filament resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing server permissions...');
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Ensure roles exist
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'admin']
        );
        
        $panelUserRole = Role::firstOrCreate(
            ['name' => 'panel_user', 'guard_name' => 'admin']
        );
        
        $editorRole = Role::firstOrCreate(
            ['name' => 'editor', 'guard_name' => 'admin']
        );
        
        $this->info('Roles ensured.');
        
        // Optionally assign roles to users if the flag is provided
        if ($this->option('assign-users')) {
            $this->assignRolesToUsers();
        }
        
        $this->info('Server permissions fixed successfully!');
        $this->info('Please clear your cache and try accessing the admin panel again.');
        
        // Show existing admins
        $admins = Admin::all();
        if ($admins->count() > 0) {
            $this->info('Existing admins:');
            foreach ($admins as $admin) {
                $roles = $admin->roles->pluck('name')->implode(', ');
                $this->line("- {$admin->name} ({$admin->email}) - Roles: {$roles}");
            }
        } else {
            $this->warn('No admins found in the database.');
        }
    }
    
    /**
     * Assign roles to existing users.
     */
    protected function assignRolesToUsers()
    {
        $this->info('Assigning roles to existing users...');
        
        // Get all admins
        $admins = Admin::all();
        
        if ($admins->count() == 0) {
            $this->warn('No admins found to assign roles to.');
            return;
        }
        
        // Check if any admin has super_admin role
        $superAdminExists = false;
        foreach ($admins as $admin) {
            if ($admin->hasRole('super_admin')) {
                $superAdminExists = true;
                break;
            }
        }
        
        // Assign super_admin role to the first admin if no super_admin exists
        if (!$superAdminExists && $admins->count() > 0) {
            $firstAdmin = $admins->first();
            if (!$firstAdmin->hasRole('super_admin')) {
                $firstAdmin->assignRole('super_admin');
                $this->info("Assigned super_admin role to {$firstAdmin->name} ({$firstAdmin->email}).");
            }
        }
        
        // Assign editor role to remaining admins
        foreach ($admins as $admin) {
            if (!$admin->hasRole('super_admin') && !$admin->hasRole('editor') && !$admin->hasRole('panel_user')) {
                $admin->assignRole('editor');
                $this->info("Assigned editor role to {$admin->name} ({$admin->email}).");
            }
        }
    }
}