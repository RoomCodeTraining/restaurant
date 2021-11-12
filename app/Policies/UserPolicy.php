<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public const USER_MANAGE = 'user.*';
    public const USER_LIST = 'user.list';
    public const USER_CREATE = 'user.create';
    public const USER_UPDATE = 'user.update';
    public const USER_DELETE = 'user.delete';
    public const USER_DEACTIVATE = 'user.deactivate';

    public function manage(User $loggedInUser)
    {
        if ($loggedInUser->can(self::USER_MANAGE)) {
            return true;
        }
    }

    public function viewAny(User $loggedInUser)
    {
        if ($loggedInUser->can(self::USER_LIST)) {
            return true;
        }
    }

    public function view(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can(self::USER_LIST)) {
            return true;
        }
    }

    public function create(User $loggedInUser)
    {
        if ($loggedInUser->can(self::USER_CREATE)) {
            return true;
        }
    }

    public function update(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can(self::USER_UPDATE)) {
            return true;
        }
    }

    public function delete(User $loggedInUser, User $user)
    {
        if ($user->id !== $loggedInUser->id && $loggedInUser->can(self::USER_DELETE)) {
            return true;
        }
    }
}
