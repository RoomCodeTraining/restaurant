<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportingPolicy
{
    use HandlesAuthorization;

    public const REPORTING_ORDERS = 'reporting.orders';
    public const REPORTING_ACCOUNT = 'reporting.account';

    public function viewOrders(User $user)
    {
        // return true;
        if ($user->can(self::REPORTING_ORDERS)) {
            return true;
        }
    }

    public function viewAccount(User $user)
    {
        // return true;
        if ($user->can(self::REPORTING_ACCOUNT)) {
            return true;
        }
    }
}
