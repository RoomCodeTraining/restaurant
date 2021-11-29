<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * @param  User  $user
     */
    public function created(User $user): void
    {
        $this->logPasswordHistory($user);
    }

    /**
     * @param  User  $user
     */
    public function updated(User $user): void
    {
        // Only log password history on update if the password actually changed
        if ($user->isDirty('password')) {
            $this->logPasswordHistory($user);
        }
    }

    /**
     * @param  User  $user
     */
    private function logPasswordHistory(User $user): void
    {
        $user->passwordHistories()->create([
            'password' => $user->password, // Password already hashed & saved so just take from model
        ]);
    }
}
