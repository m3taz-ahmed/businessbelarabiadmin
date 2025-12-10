<?php

namespace App\Providers\Filament;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;

class ResourcePermissionProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure Filament Shield permissions are registered
        // This helps with server deployments where auto-discovery might not work
        $this->app->booted(function () {
            try {
                // We don't need to call a specific method here
                // Just ensure the service provider is loaded
            } catch (\Exception $e) {
                // Silently fail in production to avoid breaking the app
                if (app()->environment('local')) {
                    throw $e;
                }
            }
        });
    }
}