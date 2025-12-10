<?php

namespace App\Filament\Resources\Roles;

use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource as BaseRoleResource;
use UnitEnum;

class RoleResource extends BaseRoleResource
{
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    
    // Disable global search for this resource
    protected static bool $isGloballySearchable = false;
}