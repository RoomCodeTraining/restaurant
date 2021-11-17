<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserTypePolicy
{
    use HandlesAuthorization;

    public const USER_TYPE_MANAGE = 'user_type.*';
    public const USER_TYPE_LIST = 'user_type.list';
    public const USER_TYPE_CREATE = 'user_type.create';
    public const USER_TYPE_UPDATE = 'user_type.update';
    public const USER_TYPE_DELETE = 'user_type.delete';

    public function manage(User $user)
    {
        if ($user->can(self::USER_TYPE_MANAGE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->can(self::USER_TYPE_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, UserType $userType)
    {
        if ($user->can(self::USER_TYPE_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can(self::USER_TYPE_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserType $userType)
    {
        if ($user->can(self::USER_TYPE_UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserType $userType)
    {
        if ($user->can(self::USER_TYPE_DELETE)) {
            return true;
        }
    }
}
