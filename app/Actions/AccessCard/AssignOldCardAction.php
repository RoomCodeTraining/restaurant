<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;

class AssignOldCardAction
{
    public function handle(User $user, AccessCard $accessCard, array $input)
    {

        $user->assign($accessCard, $input);

        return $accessCard;
    }
}