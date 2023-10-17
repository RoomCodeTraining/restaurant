<?php

namespace App\Support;

use \App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class PasswordHistoryChecker
{
  public function validatePassword(User $user, string $password): bool
  {
    $recentPasswords = $user->passwordHistories()->latest()->take(3)->get();
    foreach ($recentPasswords as $key => $recentPassword) {
      if (Hash::check($password, $recentPassword->password)) {
        return true;
      }
    }
    return false;
  }

  public function passwordHasSecure($password){
    return Validator::make(['password' => $password], [
      'password' => [
        'required',
        'confirmed',
        'different:current_password',
        function ($attribute, $value, $fail) {
          if ((new PasswordHistoryChecker)->validatePassword(auth()->user(), $value)) {
            $fail('Le mot de passe a déjà été utilisé.');
          }
        },
        Password::min(8)
          ->mixedCase()
          ->letters()
          ->numbers()
          ->symbols()
          ->uncompromised(),
      ],
    ])->validate();
  }
}
