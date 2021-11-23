<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccessCardResource;
use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AccessCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AccessCardResource::collection(AccessCard::with('user', 'paymentMethod')->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', AccessCard::class);

        $request->validate([
            'user_id' => ['required', Rule::exists('users', 'identifier')],
            'identifier' => ['required', Rule::unique('access_cards', 'identifier')],
            'quota_breakfast' => ['required', 'integer', 'min:0', 'max:25'],
            'quota_lunch' => ['required', 'integer', 'min:0', 'max:25'],
            'payment_method_id' => ['nullable', 'integer', Rule::exists('payment_methods', 'id')],
        ]);

        // TODO: Support user id and user identifier
        $user = User::with('userType.paymentMethod')->where('identifier', $request->user_id)->orWhere('id', $request->user_id)->first();

        if ($user->isFromlunchroom()) {
            throw ValidationException::withMessages([
                'user_id' => ['Ce utilisateur est un personnel de la cantine'],
            ]);
        }

        DB::beginTransaction();

        $accessCard = $user->accessCards()->create([
            'identifier' => $request->identifier,
            'quota_breakfast' => $request->get('quota_breakfast', 0),
            'quota_lunch' => $request->get('quota_lunch', 0),
            'payment_method_id' => $request->get('payment_method_id', $user->userType->paymentMethod->id),
        ]);

        $user->update([ 'current_access_card_id' => $accessCard->id ]);

        DB::commit();

        return new AccessCardResource($accessCard);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return \Illuminate\Http\Response
     */
    public function show(AccessCard $accessCard)
    {
        return new AccessCardResource($accessCard);
    }

    public function reloadAccessCard(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', Rule::exists('access_cards', 'identifier')]
        ]);

        $accessCard = AccessCard::whereIdentifier($request->identifier)->first();
        if ($accessCard->quota_lunch == 0 && $accessCard->quota_breakfast == 0) {
            $accessCard->update(['quota_lunch' => 25, 'quota_breakfast' => 25]);
        } elseif ($accessCard->quota_lunch == 0) {
            $accessCard->update(['quota_lunch' => 25]);
        } elseif ($accessCard->quota_breakfast == 0) {
            $accessCard->update(['quota_breakfast' => 25]);
        } else {
            return response()->json(['msg' => 'Cette carte a toujours des cota de petit déjeuner et déjeuner']);
        }

        return new AccessCardResource($accessCard);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessCard  $accessCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessCard $accessCard)
    {
        $accessCard->delete();

        return response()->json(['success' => 'La carte RFID a été supprimé']);
    }
}
