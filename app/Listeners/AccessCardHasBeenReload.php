<?php

namespace App\Listeners;

use App\Events\UpdatedAccessCard;

class AccessCardHasBeenReload
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdatedAccessCard  $event
     * @return void
     */
    public function handle(UpdatedAccessCard $event)
    {
        $event->accessCard->reloadAccessCardHistory()->create([
          'quota_type' => $event->quota_type,
          'quota' => config('cantine.quota_breakfast')
        ]);
    }
}