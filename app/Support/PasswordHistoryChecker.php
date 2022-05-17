<?php

namespace App\Support;

use \App\Models\User;
use Illuminate\Support\Facades\Hash;


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
}
