<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // IMPORTANT: Should not be changed
    // Base User roles ID, does not rely on the name because it can be changed
    public const ADMIN = 1;
    public const ADMIN_RH = 2;
    public const ADMIN_LUNCHROOM = 3;
    public const OPERATOR_LUNCHROOM = 5;
    public const ACCOUNTANT = 4;
    public const USER = 6;
}
