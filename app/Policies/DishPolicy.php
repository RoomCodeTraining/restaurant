<?php

namespace App\Policies;

use App\Models\Dish;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DishPolicy
{
    public const MENU_MANAGE = 'menu.*';
    public const MENU_LIST = 'menu.list';
    public const MENU_CREATE = 'menu.create';
    public const MENU_UPDATE = 'menu.update';
    public const MENU_DELETE = 'menu.delete';

    use HandlesAuthorization;

    public function manage(User $user)
    {
        if ($user->can(self::MENU_MANAGE)) {
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
        if ($user->can(self::MENU_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Dish $dish)
    {
        if ($user->can(self::MENU_LIST)) {
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
        if ($user->can(self::MENU_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Dish $dish)
    {
        if ($user->can(self::MENU_UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Dish $dish)
    {
        if ($user->can(self::MENU_DELETE)) {
            return true;
        }
    }
}
