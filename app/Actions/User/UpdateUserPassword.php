<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\PasswordHistoryChecker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UpdateUserPassword
{
    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $data, $expired = false)
    {
        $user->password = Hash::make($data['password']);
        dd($user->password);

        $user->forceFill([
        'password' => Hash::make($data['password']),
        'password_changed_at' => now(),
        ])->save();

        $user->passwordHistories()->create([
         'password' => Hash::make($data['password']),
        ]);
    }
}
