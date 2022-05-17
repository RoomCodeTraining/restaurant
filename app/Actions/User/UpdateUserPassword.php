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
    public function update($user, array $input, $expired = false)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols(), function ($attribute, $value, $fail) {
                if ($this->passwordIsAlreadyUsed($value)) {
                    $fail("Vous avez déjà utilisé ce mot de passe, veuillez le changer.");
                }
            }],
        ])->after(function ($validator) use ($user, $input) {
            if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('Le mot de passe saisi ne correspond pas à votre mot de passe actuel.'));
            }
        })->validate();

        // Reset the expiration clock
      

        $user->password = $input['password'];

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'password_changed_at' => now(),
        ])->save();
    }


    private function passwordIsAlreadyUsed(string $password) : bool
    {
       $passwordChecker = new PasswordHistoryChecker();
       return $passwordChecker->validatePassword(auth()->user(), $password);
    }
}
