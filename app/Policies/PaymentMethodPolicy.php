<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentMethodPolicy
{
    use HandlesAuthorization;

    public const PAYMENT_METHOD_MANAGE = 'payment_method.*';
    public const PAYMENT_METHOD_LIST = 'payment_method.list';
    public const PAYMENT_METHOD_CREATE = 'payment_method.create';
    public const PAYMENT_METHOD_UPDATE = 'payment_method.update';
    public const PAYMENT_METHOD_DELETE = 'payment_method.delete';

    public function manage(User $user)
    {
        if ($user->can(self::PAYMENT_METHOD_MANAGE)) {
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
        if ($user->can(self::PAYMENT_METHOD_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PaymentMethod $paymentMethod)
    {
        if ($user->can(self::PAYMENT_METHOD_LIST)) {
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
        if ($user->can(self::PAYMENT_METHOD_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PaymentMethod $paymentMethod)
    {
        if ($user->can(self::PAYMENT_METHOD_UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PaymentMethod $paymentMethod)
    {
        if ($user->can(self::PAYMENT_METHOD_DELETE)) {
            return true;
        }
    }
}
