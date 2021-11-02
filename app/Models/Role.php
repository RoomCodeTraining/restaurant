<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // IMPORTANT: Should not be changed
    // Base User roles ID, does not rely on the name because it can be changed

    public const SUPER_ADMIN = 1;
    public const ADMIN_RH = 2;
    public const ADMIN_LUNCHROOM = 3;
    public const ADMIN_ACCOUNTANT = 4;
    public const OPERATOR_LUNCHROOM = 5;
    public const USER = 6;

    public function isSuperAdmin(): bool
    {
        return $this->id === Role::SUPER_ADMIN;
    }

    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }
}
