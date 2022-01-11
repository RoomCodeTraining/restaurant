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

class AccessCardsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(AccessCard::class, 'card');
    }

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
        $request->validate([
            'user_id' => ['required'],
            'identifier' => ['required', Rule::unique('access_cards', 'identifier')],
            'quota_breakfast' => ['required', 'integer', 'min:0', 'max:25'],
            'quota_lunch' => ['required', 'integer', 'min:0', 'max:25'],
            'payment_method_id' => ['nullable', 'integer', Rule::exists('payment_methods', 'id')],
        ]);

        $user = User::with('userType.paymentMethod')->where('identifier', $request->user_id)->orWhere('id', $request->user_id)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'user_id' => ['Cet utilisateur n\'existe pas'],
            ]);
        }

        if ($user->isFromlunchroom()) {
            throw ValidationException::withMessages([
                'user_id' => ['Cet utilisateur ne peut disposer d\'une carte RFID'],
            ]);
        }

        DB::beginTransaction();

        $accessCard = $user->accessCards()->create([
            'identifier' => $request->identifier,
            'quota_breakfast' => $request->get('quota_breakfast', 0),
            'quota_lunch' => $request->get('quota_lunch', 0),
            'payment_method_id' => $request->get('payment_method_id', $user->userType->paymentMethod->id),
        ]);

        $user->update(['current_access_card_id' => $accessCard->id]);

        DB::commit();

        return new AccessCardResource($accessCard);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessCard  $card
     * @return \Illuminate\Http\Response
     */
    public function show(AccessCard $card)
    {
        return new AccessCardResource($card->load('user', 'paymentMethod'));
    }

    public function reloadAccessCard(Request $request)
    {
      
 
        $request->validate([
            'identifier' => ['required', function($attrubute, $value, $fail){
                 if(AccessCard::whereIdentifier($value)){
                     $fail("Cette Carte RFID n'existe pas. Veuillez verifier l'identifiant entré");
                 }
            }],
            'quota_type' => ['required', 'string', Rule::in(['quota_lunch', 'quota_breakfast'])],
            'quota' => ['required', 'integer', 'min:0', 'max:25'],
        ]);

        $card = AccessCard::with('user', 'paymentMethod')->where('identifier', $request->identifier)->first();
        $card->update([ $request->quota_type => $request->quota ]);

        return response()->json([
            'message' => "Le quota a été rechargé avec succès.",
            "success" => true,
            "data" => new AccessCardResource($card),
        ]);
    }
}
