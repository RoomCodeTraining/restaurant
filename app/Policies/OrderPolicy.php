<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

     /**
     * Determine whether the user can view any on the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */


    public function viewAny(User $loggedInUser)
    {
        if ($loggedInUser->can('order.list')) {
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

    public function view(User $loggedInUser, Order $order)
    {
        if ($loggedInUser->id == $order->user_id && $loggedInUser->can('order.list')) {
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

    public function create(User $loggedInUser)
    {
        if ($loggedInUser->can('order.create')) {
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

    public function update(User $loggedInUser, User $user)
    {
        if ($order->user_id == $loggedInUser->id && $loggedInUser->can('order.update')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function delete(User $loggedInUser, Order $order)
    {
        if ($order->user_id == $loggedInUser->id && $loggedInUser->can('order.delete')) {
            return true;
        }
    }

     /**
     * Determine whether the user can confirm the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function confirm(User $loggedInUser){
        if($loggedInUser->can('order.confirm')){
            return true;
         }
    }

    /**
     * Determine whether the user can validated order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function validate(User $loggedInUser){
        if($loggedInUser->can('order.validate')){
            return true;
        }
    }

}
