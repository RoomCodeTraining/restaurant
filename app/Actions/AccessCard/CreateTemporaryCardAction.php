<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;

class CreateTemporaryCardAction
{
    public function handle(User $user, array $input): AccessCard
    {
        $accessCard = $user->accessCards()->create([
            'identifier' => $input['identifier'],
            'quota_breakfast' => $input['quota_breakfast'],
            'quota_lunch' => $input['quota_lunch'],
            'payment_method_id' => $user->accessCard->paymentMethod->id,
            'type' => AccessCard::TYPE_TEMPORARY,
            'expires_at' => $input['expires_at'],
        ]);

        return $accessCard;
    }
}
