<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;

class CreatePrimaryCardAction
{
  public function handle(User $user, array $input): AccessCard
  {
    // $hasCurrentAccessCard = $user->currentAccessCard;

    $accessCard = $user->accessCards()->create([
      'identifier' => $input['identifier'],
      'quota_breakfast' => $user->currentAccessCard->quota_breakfast ?? $input['quota_breakfast'],
      'quota_lunch' => $user->currentAccessCard->quota_lunch ?? $input['quota_lunch'],
      'payment_method_id' => $input['payment_method_id'] ?? $user->userType->paymentMethod->id,
      'is_used' => true,
      'type' => AccessCard::TYPE_PRIMARY,
    ]);

    $this->accessCardHasNewQuota($accessCard);
    return $accessCard;
  }


  public function accessCardHasNewQuota($accessCard)
  {

    if ($accessCard->quota_breakfast != 0) {
      $accessCard->createReloadHistory('breakfast');
    }

    if ($accessCard->quota_lunch != 0) {
      $accessCard->createReloadHistory('lunch');
    }

    return $accessCard;
  }
}
