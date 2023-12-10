<?php

namespace App\Http\Controllers\API;

use App\Actions\AccessCard\CreateAccessCardAction;
use App\Actions\AccessCard\LogActionMessage;
use App\Events\UpdatedAccessCard;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReloadCardRequest;
use App\Http\Requests\StoreCurrentCardRequest;
use App\Http\Requests\StoreTemporaryCardRequest;
use App\Http\Resources\AccessCardResource;
use App\Models\AccessCard;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

/**
 * @group  Gestion des cartes
 * @authenticated
 * Resource API pour la gestion des cartes RFID des collaborateurs
 */
class AccessCardsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(AccessCard::class, 'card');
    }

    /**
     * Retourne la liste des cartes RFID
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AccessCardResource::collection(AccessCard::with('user', 'paymentMethod')->latest()->paginate());
    }

    /**
     * Assignation de Carte primaire
     *
     * Cette endpoint permet d'assigner une carte primaire(Carte courante) à un utilisateur
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignCurrentCard(StoreCurrentCardRequest $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);
        $validated = $request->validated();

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
    *  Assigner une carte temporaire
    *
    * Cette endpoint permet d'assigner une carte temporaire à un utilisateur
    * @param  \Illuminate\Http\Request  $request
    * @bodyParam user_id string required The user identifier or id
    * @bodyParam identifier string required The card identifier
    * @bodyParam expires_at date required The card expiration date
    */
    public function assignTemporaryCard(StoreTemporaryCardRequest $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);

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
     * Afficher les informations d'une carte RFID
     *
     * Cette endpoint permet d'afficher les informations d'une carte RFID
     * @param  \App\Models\AccessCard  $card
     * @return \Illuminate\Http\Response
     */
    public function show(AccessCard $card)
    {
        return new AccessCardResource($card->load('user', 'paymentMethod'));
    }

    /**
     * Recharger une carte RFID
     *
     * Cette endpoint permet de recharger une carte RFID
     * @param Request $request
     * @param LogActionMessage $action
     * @return JsonResponse
     */
    public function reloadAccessCard(ReloadCardRequest $request, LogActionMessage $action)
    {
        $validated = $request->validated();

        $card = AccessCard::with('user', 'paymentMethod')->where('identifier', $validated['access_card_identifier'])->first();
        $old_quota = $card[$request->quota_type];

        /*
        * Mise a jour du nombre de fois le quota a été rechargé
        */

        $type = $validated['quota_type'] == 'quota_lunch' ? 'lunch' : 'breakfast';

        $card->createReloadHistory($type);

        if ($old_quota > 0) {
            return response()->json([
              'message' => "Le quota de l'utilisateur n'est pas épuisé, vous ne pouvez pas recharger son compte.",
              'success' => false,
              'data' => new AccessCardResource($card),
            ], 422);
        }



        $card->update([$validated['quota_type'] => $request->quota + $old_quota]);

        $quota_title = $type == 'lunch' ? 'dejeuner' : 'petit déjeuner';

        UpdatedAccessCard::dispatch($card, $type);

        activity()
          ->causedBy(Auth()->user())
          ->performedOn($card)
          ->event("La carte de l'utilisateur {$card->user->full_name} a été rechargée par ".auth()->user()->full_name.". Le nouveau quota de {$quota_title} est de {$validated['quota']}.")
          ->log('Rechargement de carte RFID');

        return response()->json([
          'message' => "Le quota a été rechargé avec succès.",
          "success" => true,
          "data" => new AccessCardResource($card),
        ]);
    }

    /**
     * Recuperer de la carte courante de l'utilisateur
     *
     * Cette endpoint permet de recuperer la carte courante d'un utilisateur
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