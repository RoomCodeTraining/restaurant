<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Events\UpdatedAccessCard;


class LogActionMessage
{
    public function execute(AccessCard $card, string $type)
    {

      $quota = (int) $card['quota_'.$type];

      $typeInFrench = $type == 'lunch' ? 'déjeuner' : 'petit-déjeuner';
      $message =  "Le quota ". $typeInFrench .' de '. $card->user->full_name . " a été rechargée par " . auth()->user()->full_name . ". Le nouveau quota ".$typeInFrench." est de " . $quota.'.';
      activity()
      ->causedBy(Auth()->user())
      ->performedOn($card)
      ->event($message)
      ->log('Rechargement de carte RFID');
      UpdatedAccessCard::dispatch($card, $type);

      return $card;
    }
}
