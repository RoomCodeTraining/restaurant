<?php

namespace App\Http\Controllers\API;

use App\Actions\AccessCard\CreateAccessCardAction;
use App\Actions\AccessCard\LogActionMessage;
use App\Events\UpdatedAccessCard;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccessCardResource;
use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

/**
 * @group Access Cards
 * @authenticated
 * APIs for managing NFC cards
 */
class AccessCardsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(AccessCard::class, 'card');
    }

    /**
     * Display a listing of the resource.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AccessCardResource::collection(AccessCard::with('user', 'paymentMethod')->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *  @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
      * @bodyParam user_id string required The user identifier or id
      * @bodyParam identifier string required The card identifier
      * @bodyParam quota_breakfast integer required The breakfast quota
      * @bodyParam quota_lunch integer required The lunch quota
      * @bodyParam payment_method_id integer required The payment method id
     */
    public function assignCurrentCarrd(Request $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);
        $validated = $request->validate([
          'user_id' => ['required', Rule::exists('users', 'id')],
          'identifier' => ['required', 'string', 'max:255', Rule::unique('access_cards', 'identifier')],
          'quota_breakfast' => ['nullable', Rule::requiredIf(! $request->is_temporary), 'integer', 'min:0', 'max:25'],
          'quota_lunch' => ['nullable', Rule::requiredIf(! $request->is_temporary), 'integer', 'min:0', 'max:25'],
          'payment_method_id' => ['nullable', Rule::exists('payment_methods', 'id')],
        ]);

        $user = User::where('id', $validated['user_id'])->orWhere('identifier', $validated['user_id'])->with('userType.paymentMethod')->first();

        if($user->accessCard) {
            return response()->json([
              'message' => 'Cet utilisateur a déjà une carte associée à son compte',
              'success' => false,
            ], 422);
        }

        if(! $user) {
            return response()->json([
              'message' => 'Cet utilisateur n\'existe pas',
              'success' => false,
            ], 422);
        }

        if($user->isFromlunchroom()) {
            return response()->json([
              'message' => 'Cet utilisateur ne peut pas disposer d\'une carte',
              'success' => false,
            ], 422);
        }

        $accessCard = $createAccessCardAction->handle($user, array_merge($validated, ['is_temporary' => false]), $validated);

        if($accessCard->quota_lunch > 0) {
            event(new UpdatedAccessCard($accessCard, 'lunch'));
            $accessCard->createReloadHistory('lunch');
        }

        if($accessCard->quota_breakfast > 0) {
            event(new UpdatedAccessCard($accessCard, 'breakfast'));
            $accessCard->createReloadHistory('breakfast');
        }

        return new AccessCardResource($accessCard);
    }

    /**
    * Assign a temporary card to a user
    * @authenticated
    * @param  \Illuminate\Http\Request  $request
    * @bodyParam user_id string required The user identifier or id
    * @bodyParam identifier string required The card identifier
    * @bodyParam expires_at date required The card expiration date
    */
    public function assignTemporaryCard(Request $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);

        $validated = $request->validate([
          'user_id' => ['required', Rule::exists('users', 'id')],
          'identifier' => ['required', 'string', 'max:255'],
          'expires_at' => ['nullable', Rule::requiredIf((bool) $request->is_temporary), 'date', 'after_or_equal:today'],
        ]);

        $card = AccessCard::where(['identifier' => $request->identifier, 'type' => 'temporary'])->first();

        if($card && $card->is_used) {
            return response()->json([
              'message' => 'Cette carte est déjà utilisée',
              'success' => false,
            ], 422);
        }

        $user = User::with('userType.paymentMethod')->where('identifier', $request->user_id)->orWhere('id', $request->user_id)->first();

        if (! $user->accessCard) {
            return response()->json([
              'message' => "Cet utilisateur ne peut disposer de carte temporaire car il n'a pas de carte RFID",
              'success' => false,
            ], 422);
        }

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

        if ($user->accessCard && $user->accessCard->type === AccessCard::TYPE_TEMPORARY) {
            return response()->json([
              'message' => 'Cet utilisateur a déjà une carte temporaire associée à son compte',
              'success' => false,
            ], 422);
        }

        $accessCard = $createAccessCardAction->handle($user, array_merge($validated, ['is_temporary' => true]));

        activity()
          ->causedBy(Auth()->user())
          ->performedOn($accessCard)
          ->event("La carte RFID de N° {$accessCard->identifier} vient d'être associée au compte de {$accessCard->user->full_name}")
          ->log('Rechargement de carte RFID');

        return new AccessCardResource($accessCard);
    }


    /**
     * Display the specified resource.
     * @authenticated
     * @param  \App\Models\AccessCard  $card
     * @return \Illuminate\Http\Response
     */
    public function show(AccessCard $card)
    {
        return new AccessCardResource($card->load('user', 'paymentMethod'));
    }

    public function reloadAccessCard(Request $request, LogActionMessage $action)
    {


        $request->validate([
          'identifier' => ['required', function ($attrubute, $value, $fail) {
              if (! AccessCard::whereIdentifier($value)) {
                  $fail("Cette Carte RFID n'existe pas. Veuillez verifier l'identifiant entré");
              }
          }],
          'quota_type' => ['required', 'string', Rule::in(['quota_lunch', 'quota_breakfast'])],
          'quota' => ['required', 'integer', 'min:0', 'max:25'],
        ]);



        $card = AccessCard::with('user', 'paymentMethod')->where('identifier', $request->identifier)->first();
        $old_quota = $card[$request->quota_type];



        /*
        * Mise a jour du nombre de fois le quota a été rechargé
        */

        $type = $request->quota_type == 'quota_lunch' ? 'lunch' : 'breakfast';

        $card->createReloadHistory($type);

        if ($old_quota > 0) {
            return response()->json([
              'message' => "Le quota de l'utilisateur n'est pas épuisé, vous ne pouvez pas recharger son compte.",
              'success' => false,
              'data' => new AccessCardResource($card),
            ], 422);
        }



        $card->update([$request->quota_type => $request->quota + $old_quota]);

        $quota_title = $type == 'lunch' ? 'dejeuner' : 'petit déjeuner';

        UpdatedAccessCard::dispatch($card, $type);

        activity()
          ->causedBy(Auth()->user())
          ->performedOn($card)
          ->event("La carte de l'utilisateur {$card->user->full_name} a été rechargée par ".auth()->user()->full_name.". Le nouveau quota de {$quota_title} est de {$request->quota}.")
          ->log('Rechargement de carte RFID');

        return response()->json([
          'message' => "Le quota a été rechargé avec succès.",
          "success" => true,
          "data" => new AccessCardResource($card),
        ]);
    }

    /**
     * Get the current access card of a users
      * @authenticated
     * @param Request $request
     * @return AccessCardResource
     * @throws InvalidArgumentException
     */
    public function currentAccessCard(Request $request)
    {
        $request->validate([
          'identifier' => ['required', 'string', Rule::exists('users', 'identifier')],
        ]);

        $user = User::with('currentAccessCard')->where('identifier', $request->identifier)->first();

        return new AccessCardResource($user->currentAccessCard);
    }
}