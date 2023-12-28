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
    public const ADMIN_TECHNICAL = 7;
    public const DATA_ANALYST = 8;
    public const DEVELOPER = 9;



    public static function getRole(string $role)
    {

        switch ($role) {
            case 'utilisateur':
                return Role::USER;
                break;
            case 'admin':
                return Role::ADMIN;
                break;
            case 'admin_rh':
                return Role::ADMIN_RH;
                break;
            case 'admin_cantine':
                return Role::ADMIN_LUNCHROOM;
                break;
            case 'operator_cantine':
                return Role::OPERATOR_LUNCHROOM;
                break;
            default:
                return Role::ACCOUNTANT;

                break;
        }
    }
}
