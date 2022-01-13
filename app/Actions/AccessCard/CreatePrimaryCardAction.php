<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;

class CreatePrimaryCardAction
{
    public function handle(User $user, array $input): AccessCard
    {
        $accessCard = $user->accessCards()->create([
            'identifier' => $input['identifier'],
            'quota_breakfast' => $input['quota_breakfast'],
            'quota_lunch' => $input['quota_lunch'],
            'payment_method_id' => $input['payment_method_id'] ?? $user->userType->paymentMethod->id,
            'type' => AccessCard::TYPE_PRIMARY,
        ]);

        return $accessCard;
    }
}
