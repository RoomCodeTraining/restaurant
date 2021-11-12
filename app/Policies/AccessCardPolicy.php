<?php

namespace App\Policies;

use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessCardPolicy
{
    use HandlesAuthorization;

    public const ACCESS_CARD_MANAGE = 'access_card.*';
    public const ACCESS_CARD_LIST = 'access_card.list';
    public const ACCESS_CARD_CREATE = 'access_card.create';
    public const ACCESS_CARD_DELETE = 'access_card.delete';
    public const ACCESS_CARD_TOPUP = 'access_card.top_up';

    public function manage(User $user)
    {
        if ($user->can(self::ACCESS_CARD_MANAGE)) {
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
        if ($user->can(self::ACCESS_CARD_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessCard  $accessCard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AccessCard $accessCard)
    {
        if ($user->can(self::ACCESS_CARD_LIST)) {
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
        if ($user->can(self::ACCESS_CARD_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessCard  $accessCard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AccessCard $accessCard)
    {
        if ($user->can(self::ACCESS_CARD_DELETE)) {
            return true;
        }
    }
}
