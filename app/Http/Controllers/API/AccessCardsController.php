<?php

namespace App\Http\Controllers\API;

use App\Actions\AccessCard\AssignOldCardAction;
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
        return AccessCardResource::collection(
            AccessCard::with('user', 'paymentMethod')
                ->latest()
                ->paginate(),
        );
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

        $user = User::where('id', $validated['user_id'])
            ->with('userType.paymentMethod')
            ->first();
        dd($user);
        $accessCard = AccessCard::where('identifier', $validated['identifier'])->first();



        if ($user->accessCard) {
            return $this->responseUnprocessable('Cet utilisateur possède déjà une carte.', 'Carte déjà attribuée');
        }

        if ($accessCard && $accessCard->is_used) {
            return $this->responseBadRequest("La carte en question est déjà en cours d'utilisation.", 'Carte utilisée');
        }

        if (! $user) {
            return $this->responseNotFound("Aucun utilisateur correspondant n'a été identifié.", 'Utilisateur non trouvé');
        }
        if ($user->isFromlunchroom()) {
            return $this->responseBadRequest('Cet utilisateur ne peut pas obtenir une carte.', "Non autorisé");
        }

        if ($request->assign_quota) {
            $validated['quota_lunch'] = config('cantine.quota_lunch');
            if ($user->Organization->isGroup1() || $user->is_entitled_breakfast) {
                $validated['quota_breakfast'] = config('cantine.quota_breakfast');
            } else {
                $validated['quota_breakfast'] = 0;
            }
        } else {
            $validated['quota_lunch'] = 0;
            $validated['quota_breakfast'] = 0;
        }

        unset($validated['assign_quota']);

        if (! $accessCard) {
            $accessCard = $createAccessCardAction->handle($user, array_merge($validated, ['is_temporary' => false]), $validated);
        } else {
            (new AssignOldCardAction())->handle($user, $accessCard, array_merge($validated, ['is_temporary' => false]));
        }

        // if ($accessCard->quota_lunch > 0) {
        //     event(new UpdatedAccessCard($accessCard, 'lunch'));
        //     $accessCard->createReloadHistory('lunch');
        // }

        // if ($accessCard->quota_breakfast > 0) {
        //     event(new UpdatedAccessCard($accessCard, 'breakfast'));
        //     $accessCard->createReloadHistory('breakfast');
        // }

        return $this->responseCreated('Attribution réussie de la carte primaire.', new AccessCardResource($accessCard));
    }

    /**
     *  Assigner une carte temporaire
     *
     * Cette endpoint permet d'assigner une carte temporaire à un utilisateur
     * @param  \Illuminate\Http\Request  $request
     */
    public function assignTemporaryCard(StoreTemporaryCardRequest $request, CreateAccessCardAction $createAccessCardAction)
    {
        $this->authorize('create', AccessCard::class);

        $card = AccessCard::where(['identifier' => $request->access_card_identifier])->first();

        if ($card && $card->is_used) {
            return $this->responseBadRequest("La carte en question est déjà en cours d'utilisation.", 'Carte utilisée');
        }

        $user = User::with('userType.paymentMethod')
            ->where('identifier', $request->user_id)
            ->orWhere('id', $request->user_id)
            ->first();

        if (! $user) {
            return $this->responseNotFound("Aucun utilisateur correspondant n'a été identifié.", 'Utilisateur non trouvé');
        }

        if ($user->isFromlunchroom()) {
            return $this->responseBadRequest('Cet utilisateur ne peut pas obtenir une carte.', "Erreur lors de l'assignation");
        }

        if ($user->accessCard && $user->accessCard->type === AccessCard::TYPE_TEMPORARY) {
            return $this->responseBadRequest('Cet utilisateur possède déjà une carte temporaire.', "Erreur lors de l'assignation");
        }
        if ($card) {
            $card->update([
                'is_used' => true,
                'quota_breakfast' => $user->accessCard->quota_breakfast,
                'quota_lunch' => $user->accessCard->quota_lunch,
                'payment_method_id' => $user->accessCard->paymentMethod->id,
                'type' => AccessCard::TYPE_TEMPORARY,
                'is_used' => true,
                'expires_at' => $request->expires_at,
                'user_id' => $user->id,
            ]);
            $user->attachCard($card);
            $accessCard = $card;
        } else {
            $accessCard = $createAccessCardAction->handle($user, array_merge($request->validated(), ['is_temporary' => true]));
        }

        activity()
            ->causedBy(Auth()->user())
            ->performedOn($accessCard ?? $card)
            ->event("La carte RFID de N° {$accessCard->identifier} vient d'être associée au compte de {$accessCard->user->full_name}")
            ->log('Rechargement de carte RFID');

        return $this->responseCreated('Attribution réussie de la carte temporaire.', new AccessCardResource($accessCard));
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

        $card = AccessCard::with('user', 'paymentMethod')
            ->where('identifier', $validated['access_card_identifier'])
            ->first();

        $old_quota = $card[$request->quota_type];

        /*
         * Mise a jour du nombre de fois le quota a été rechargé
         */

        $type = $validated['quota_type'] == 'quota_lunch' ? 'lunch' : 'breakfast';
        $quota_title = $type == 'lunch' ? 'dejeuner' : 'petit déjeuner';

        if (! $card->user->is_entitled_breakfast) {
            return $this->responseBadRequest("Cet utilisateur n'a pas droit au petit déjeuner.", 'Erreur de rechargement');
        }

        if ($card->user->Organization->isGroup2() && $type == 'breakfast') {
            return $this->responseBadRequest("Cet utilisateur n'a pas droit au petit déjeuner.", 'Erreur de rechargement');
        }




        if ($old_quota > config('cantine.quota_critical')) {
            return $this->responseBadRequest("Le quota {$quota_title} de cette carte n'est pas critique.", 'Erreur de rechargement');
        }

        $newQuota = config('cantine.quota_lunch') + $old_quota;
        $card->update([$validated['quota_type'] => $newQuota]);

        UpdatedAccessCard::dispatch($card, $type);

        $eventDescription = "La carte de l'utilisateur {$card->user?->full_name} a été rechargée par " . auth()->user()?->full_name . ". Le nouveau quota de {$quota_title} est de {$newQuota}.";

        activity()
            ->causedBy(Auth()->user())
            ->performedOn($card)
            ->event($eventDescription)
            ->log('Rechargement de carte RFID');

        return response()->json([
            'message' => 'Le quota a été rechargé avec succès.',
            'success' => true,
            'data' => new AccessCardResource($card),
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

        $user = User::with('currentAccessCard')
            ->where('identifier', $request->identifier)
            ->first();

        return new AccessCardResource($user->currentAccessCard);
    }
}