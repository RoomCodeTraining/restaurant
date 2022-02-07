<?php

namespace App\Http\Controllers\API;

use App\Actions\AccessCard\CreateAccessCardAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccessCardResource;
use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


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
    public function store(Request $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);
        
        
        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'identifier' => ['required', 'string', 'max:255'],
            'quota_breakfast' => ['nullable', Rule::requiredIf(!$request->is_temporary), 'integer', 'min:0', 'max:25'],
            'quota_lunch' => ['nullable', Rule::requiredIf(!$request->is_temporary), 'integer', 'min:0', 'max:25'],
            'is_temporary' => ['required', 'boolean'],
            'expires_at' => ['nullable', Rule::requiredIf((bool) $request->is_temporary), 'date', 'after_or_equal:today'],
            'payment_method_id' => ['nullable', Rule::exists('payment_methods', 'id')],
        ]);
        
        $hasAnAccessCard = AccessCard::where(['identifier' => $request->identifier])->exists();

        if($hasAnAccessCard){
          return response()->json([
              'message' => "Cette carte est déja utilisée par un autre collaborateur",
              'success' => false,
            ], 422);
        }
        
        $user = User::with('userType.paymentMethod')->where('identifier', $request->user_id)->orWhere('id', $request->user_id)->first();
        
        if (! $user) {
            return response()->json([
                'message' => "Cet utilisateur n'existe pas",
                'success' => false,
            ], 422);
        }

        if ($user->isFromlunchroom()) {
            return response()->json([
                'message' => "Cet utilisateur ne peut disposer d'une carte RFID",
                'success' => false,
            ], 422);
        }

        if(!$user->accessCard){
            return response()->json([
                'message' => "Cet utilisateur ne peut disposer de carte temporaire car il n'a pas de carte RFID",
                'success' => false,
            ], 422);
        }

        
    
        if ($user->accessCard && $user->accessCard->type === AccessCard::TYPE_TEMPORARY) {
            return response()->json([
                'message' => 'Cet utilisateur a déjà une carte temporaire associée à son compte',
                'success' => false,
            ], 422);
        }

        $accessCard = $createAccessCardAction->handle($user, $validated);

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
                 if(!AccessCard::whereIdentifier($value)){
                     $fail("Cette Carte RFID n'existe pas. Veuillez verifier l'identifiant entré");
                 }
            }],
            'quota_type' => ['required', 'string', Rule::in(['quota_lunch', 'quota_breakfast'])],
            'quota' => ['required', 'integer', 'min:0', 'max:25'],
        ]);
       
        $card = AccessCard::with('user', 'paymentMethod')->where('identifier', $request->identifier)->first();
        $old_quota = $card[$request->quota_type];
        $type = $request->quota_type == 'quota_lunch' ? 'déjeuner' : 'petit déjeuner';

        if($old_quota > 0){
            return response()->json([
                'message' => "Le quota de l'utilisateur n'est pas épuisé, vous ne pouvez pas recharger son compte.",
                'success' => false,
                'data' => new AccessCardResource($card),
            ], 422);
        }

   

        $card->update([ $request->quota_type => $request->quota + $old_quota ]);

        return response()->json([
            'message' => "Le quota a été rechargé avec succès.",
            "success" => true,
            "data" => new AccessCardResource($card),
        ]);
    }

    public function currentAccessCard(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string', Rule::exists('users', 'identifier')],
        ]);

         $user = User::with('currentAccessCard')->where('identifier', $request->identifier)->first();
        return new AccessCardResource($user->currentAccessCard);
    }
}
