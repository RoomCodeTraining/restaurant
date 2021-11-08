<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
     use HandlesAuthorization;

    public function viewAny(User $loggedInUser)
    {
        if ($loggedInUser->can('user.list')) {
            return true;
        }
    }

    public function view(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can('user.list')) {
            return true;
        }
    }

    public function create(User $loggedInUser)
    {
        if ($loggedInUser->can('user.create')) {
            return true;
        }
    }

    public function update(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can('user.update')) {
            return true;
        }
    }

    public function delete(User $loggedInUser, User $user)
    {
        if ($user->id !== $loggedInUser->id && $loggedInUser->can('user.delete')) {
            return true;
        }
    }


}
