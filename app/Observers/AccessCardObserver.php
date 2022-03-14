<?php

namespace App\Observers;

use App\Models\AccessCard;

class AccessCardObserver
{
    /**
     * Handle the AccessCard "created" event.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return void
     */
    public function created(AccessCard $accessCard)
    {
        //
    }

    /**
     * Handle the AccessCard "updated" event.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return void
     */
    public function updated(AccessCard $accessCard)
    {
        $accessCard->reloadAccessCardHistory()->create([
          'quota_type' => 'lunch',
          'quota' => $accessCard->quota_lunch,
        ]);
    }

    /**
     * Handle the AccessCard "deleted" event.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return void
     */
    public function deleted(AccessCard $accessCard)
    {
        //
    }

    /**
     * Handle the AccessCard "restored" event.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return void
     */
    public function restored(AccessCard $accessCard)
    {
        //
    }

    /**
     * Handle the AccessCard "force deleted" event.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return void
     */
    public function forceDeleted(AccessCard $accessCard)
    {
        //
    }
}
