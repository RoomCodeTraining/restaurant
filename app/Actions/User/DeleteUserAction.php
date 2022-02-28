<?php

namespace App\Actions\User;

use App\Models\User;
use App\Events\UserDeleted;
use App\Support\ActivityHelper;
use Illuminate\Validation\ValidationException;

final class DeleteUserAction
{
    public function execute(User $user): User
    {
        if (null !== $user->deleted_at) {
            throw_if(null !== $user->deleted_at, ValidationException::withMessages([
                'delete_user' => 'Ce utilisateur est deja supprimé.',
            ]));
        }
     
        ActivityHelper::createActivity(
          $user,
          "Suppression du compte de $user->full_name",
          'Creation de nouveau menu',
        );

        $user->delete();

        UserDeleted::dispatch($user);

        return $user;
    }
}
