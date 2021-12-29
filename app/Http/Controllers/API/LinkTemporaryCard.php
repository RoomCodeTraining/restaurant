<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AccessCardResource;
use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LinkTemporaryCard
{
    use AuthorizesRequests;

    public function __invoke(Request $request)
    {
        $this->authorize('create', AccessCard::class);

        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'identifier' => ['required'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $user = User::with('userType.paymentMethod', 'accessCard')->where('identifier', $request->user_id)->orWhere('id', $request->user_id)->first();

        if ($user->isFromlunchroom()) {
            throw ValidationException::withMessages([
                'user_id' => ['Cet utilisateur ne peut disposer d\'une carte RFID'],
            ]);
        }

        if ($user->accessCard->type === AccessCard::TYPE_TEMPORARY) {
            return response()->json([
                'message' => 'Cet utilisateur a déjà une carte temporaire associée à son compte',
            ], 422);
        }

        DB::beginTransaction();

        $accessCard = $user->accessCards()->create([
            'identifier' => $request->identifier,
            'quota_breakfast' => 1,
            'quota_lunch' => 1,
            'payment_method_id' => $user->accessCard->paymentMethod->id,
            'type' => AccessCard::TYPE_TEMPORARY,
            'expires_at' => $request->get('expires_at', today()->addDay(1)),
        ]);

        $user->update(['current_access_card_id' => $accessCard->id]);

        DB::commit();

        return new AccessCardResource($accessCard);
    }
}
