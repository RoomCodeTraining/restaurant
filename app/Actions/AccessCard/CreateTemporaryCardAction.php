<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;

class CreateTemporaryCardAction
{
    public function handle(User $user, array $input): AccessCard
    {

        $accessCard = $user->accessCards()->create([
            'identifier' => $input['access_card_identifier'],
            'quota_breakfast' => $user->currentAccessCard->quota_breakfast,
            'quota_lunch' => $user->currentAccessCard->quota_lunch,
            'payment_method_id' => $user->accessCard->paymentMethod->id,
            'type' => AccessCard::TYPE_TEMPORARY,
            'is_used' => true,
            'expires_at' => $input['expires_at'],
        ]);

        $user->attachCard($accessCard);

        return $accessCard;
    }
}