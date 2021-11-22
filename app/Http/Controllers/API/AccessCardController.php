<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Enums\UserTypes;
use App\Models\AccessCard;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccessCardResource;

class AccessCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AccessCardResource::collection(AccessCard::with('user', 'paymentMethod')->orderByDesc('created_at')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'identifier' => ['required', Rule::unique('access_cards', 'identifier')],
            'user_id' => ['required', Rule::exists('users', 'id')]
        ]);

        $user = User::findOrFail($request->user_id);
        $paymentMethod = PaymentMethod::getPaymentMethodForUser($user);
        $paymentMethodId = PaymentMethod::whereName($paymentMethod)->first()->id;

        $accessCard = AccessCard::create([
            'identifier' => $request->identifier,
            'user_id' => $request->user_id,
            'quota_breakfast' => 0,
            'quota_lunch' => 0,
            'payment_method_id' => $paymentMethodId,
        ]);

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
        if($accessCard->quota_lunch == 0 && $accessCard->quota_breakfast == 0){
            $accessCard->update(['quota_lunch' => 25, 'quota_breakfast' => 25]);
        }elseif($accessCard->quota_lunch == 0){
            $accessCard->update(['quota_lunch' => 25]);
        }elseif($accessCard->quota_breakfast == 0){
            $accessCard->update(['quota_breakfast' => 25]);
        }else{
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
