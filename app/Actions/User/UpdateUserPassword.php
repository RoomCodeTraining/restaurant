<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
    public function update($user, array $input, $expired = false)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols(), function ($attribute, $value, $fail) {
                $isRecentPassword = auth()->user()
                    ->passwordHistories()
                    ->latest()
                    ->limit(3)
                    ->exists('password', Hash::make($value));

                if ($isRecentPassword) {
                    $fail("Vous avez déjà utilisé ce mot de passe, veuillez le changer.");
                }
            }],
        ])->after(function ($validator) use ($user, $input) {
            if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('Le mot de passe saisi ne correspond pas à votre mot de passe actuel.'));
            }
        })->validate();

        // Reset the expiration clock
        if ($expired) {
            $user->password_changed_at = now();
        }

        $user->password = $input['password'];

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
