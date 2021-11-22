<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public const ORDER_MANAGE = 'order.*';
    public const ORDER_LIST = 'order.list';
    public const ORDER_CREATE = 'order.create';
    public const ORDER_UPDATE = 'order.update';
    public const ORDER_DELETE = 'order.delete';

    public function manage(User $user)
    {
        if ($user->can(self::ORDER_MANAGE)) {
            return true;
        }
    }

    /**
    * Determine whether the user can view any on the model.
    *
    * @param  \App\Models\User  $user
    * @param  \App\Models\Menu  $menu
    * @return \Illuminate\Auth\Access\Response|bool
    */
    public function viewAny(User $user)
    {
        if ($user->can(self::ORDER_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $order)
    {
        if ($user->isFromLunchroom() && $user->can(self::ORDER_LIST)) {
            return true;
        }

        if ($user->id == $order->user_id && $user->can(self::ORDER_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can(self::ORDER_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Order $order)
    {
        if ($user->isFromLunchroom() && $user->can(self::ORDER_UPDATE)) {
            return true;
        }

        if ($order->user_id == $user->id && $user->can(self::ORDER_UPDATE)) {
            return true;
        }
    }
}
