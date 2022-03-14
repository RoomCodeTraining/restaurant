<?php

namespace App\Listeners;

use App\Events\UpdatedAccessCard;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
          'quota' => $event->accessCard->{'quota_'.$event->quota_type},
        ]);
    }
}
