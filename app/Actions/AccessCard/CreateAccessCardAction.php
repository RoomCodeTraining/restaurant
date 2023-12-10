<?php

namespace App\Actions\AccessCard;

use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAccessCardAction
{
    protected CreatePrimaryCardAction $createPrimaryCardAction;
    protected CreateTemporaryCardAction $createTemporaryCardAction;

    public function __construct(CreatePrimaryCardAction $createPrimaryCardAction, CreateTemporaryCardAction $createTemporaryCardAction)
    {
        $this->createPrimaryCardAction = $createPrimaryCardAction;
        $this->createTemporaryCardAction = $createTemporaryCardAction;
    }

    public function handle(User $user, array $input): AccessCard
    {
        DB::beginTransaction();

        if ($input['is_temporary'] == true) {
            $accessCard = $this->createTemporaryCardAction->handle($user, $input);
        } else {
            $accessCard = $this->createPrimaryCardAction->handle($user, $input);
        }

        $user->useCard($accessCard);

        DB::commit();

        return $accessCard;
    }
}