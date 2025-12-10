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
    protected $signature = 'app:fix-server-permissions';

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
        
        // Assign super_admin role to Moataz
        $moataz = Admin::where('email', 'moataz@businessbelarabia.com')->first();
        if ($moataz) {
            if (!$moataz->hasRole('super_admin')) {
                $moataz->assignRole('super_admin');
                $this->info('Assigned super_admin role to Moataz.');
            } else {
                $this->info('Moataz already has super_admin role.');
            }
        } else {
            $this->warn('Moataz user not found.');
        }
        
        // Assign editor role to the other admin
        $admin = Admin::where('email', 'admin@businessbelarabia.com')->first();
        if ($admin) {
            if (!$admin->hasRole('editor')) {
                $admin->assignRole('editor');
                $this->info('Assigned editor role to admin.');
            } else {
                $this->info('Admin already has editor role.');
            }
        } else {
            $this->warn('Admin user not found.');
        }
        
        $this->info('Server permissions fixed successfully!');
        $this->info('Please clear your cache and try accessing the admin panel again.');
    }
}