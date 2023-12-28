<?php
namespace App\Models\Traits;

use App\Models\AccessCard;
use App\Models\AccessCardHistory;
use App\Support\ActivityHelper;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

trait HasAccessCard
{
    /**
     *
     * @return HasMany
     */
    public function accessCards() : HasMany
    {
        return $this->hasMany(AccessCard::class);
    }

    /**
     * Get the access card that owns the model.
     * @return BelongsTo
     */
    public function accessCard() : BelongsTo
    {
        return $this->belongsTo(AccessCard::class, 'current_access_card_id');
    }

    /**
     *
     * @param AccessCard $accessCard
     * @return bool
     */
    public function isCurrentAccessCard(AccessCard $accessCard) : bool
    {
        return $this->current_access_card_id === $accessCard->id;
    }

    public function switchAccessCard(AccessCard $accessCard) : void
    {

        $accessCard->update([
            'quota_breakfast' => $this->currentAccessCard->quota_breakfast,
            'quota_lunch' => $this->currentAccessCard->quota_lunch,
        ]);

        $this->current_access_card_id = $accessCard->id;

        $this->save();
    }

    public function assign(AccessCard $accessCard, array $input)
    {
        $this->current_access_card_id = $accessCard->id;

        $accessCard->update([
          'is_used' => true,
          'quota_breakfast' => Arr::get($input, 'quota_breakfast', 0),
          'quota_lunch' => Arr::get($input, 'quota_lunch', 0),
          'user_id' => $this->id,
          'type' => Arr::get($input, 'type', 'primary'),
          // 'payment_method_id' => Arr::get($input, 'payment_method_id'),
        ]);

        $this->attachCard($accessCard);
        $this->save();
    }

    public function attachCard(AccessCard $accessCard) : void
    {
        AccessCardHistory::create([
            'user_id' => $this->id,
            'access_card_id' => $accessCard->id,
            'type' => $accessCard->type,
            'attached_at' => now(),
        ]);

        $this->switchAccessCard($accessCard);
    }


    /**
     *
     * @param AccessCard $accessCard
     * @return void
     * @throws MassAssignmentException
     */
    public function dettachAccessCard(AccessCard $accessCard) : void
    {
        $this->current_access_card_id = null;

        $accessCard->histories()
            ->latest()->first()
            ?->update([
            'detached_at' => now(),
        ]);

        $accessCard->update([
            'is_used' => false,
             'quota_breakfast' => 0,
             'quota_lunch' => 0,
             'user_id' => null,
             'expires_at' => null,
         ]);

        ActivityHelper::createActivity(
            $this,
            "Retrait de la carte {$accessCard->identifier}",
            'Désactivation de la carte',
        );
    }

    public function destroyAccessCard(AccessCard $accessCard) : void
    {
        $accessCard->histories()->latest()->first()->update([
            'detached_at' => now(),
        ]);
        $accessCard->delete();
    }
}