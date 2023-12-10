<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AccessCard;
use App\Models\MenuSpecial;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarkOrderAsCompleted extends Controller
{
    use AuthorizesRequests;

    public function markAsLunchCompleted(Request $request)
    {
        $request->validate([
            'identifier' => ['required', Rule::exists('access_cards', 'identifier')],
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);
        $order = Order::today()->where('user_id', $accessCard->user_id)->whereState('state', [Confirmed::class, Completed::class])->first();
        $user = $accessCard->user->load('organization');



        if($user->organization->isGroup2()) {

            $specialMenu = MenuSpecial::whereDate('served_at', today());

            if(Order::firstWhere('user_id', $user->id)) {
                return $this->responseBadRequest("Vous avez déjà récupéré votre déjeuner.", "Plat déjà récupéré");
            }

            if($user->accessCard->quota_lunch <= 0) {
                return $this->responseBadRequest("Vous n'avez plus de quota pour le déjeuner.", "Non autorisé");
            }

            if($order) {
                return $this->responseBadRequest("Vous avez déjà récupéré votre déjeuner.", "Déjà récupéré");
            }

            if($specialMenu->exists()) {
                $order = Order::create([
                    'user_id' => $accessCard->user_id,
                    'type' => 'lunch',
                    'state' => 'completed',
                    'payment_method_id' => $accessCard->payment_method_id,
                    'access_card_id' => $accessCard->id,
                    'is_decrement' => true,
                    'dish_id' => $specialMenu->first()->dish_id,
                ]);

                $accessCard->decrement('quota_lunch');

                // ActivityHelper::createActivity(
                //     $accessCard->user,
                //     "Récupération du plat spécial {$order->dish->name}",
                //     'Récupération du plat spécial',
                // );

                return $this->responseSuccess("Bonjour {$accessCard->user->full_name}, votre commande de {$order->dish->name} a été marquée comme récupérée.", [
                  'order' => $order
                ]);
            }

            return $this->responseNotFound("Le menu B pour ce jour n'est pas disponible", "Menu non disponible");
        }

        if(! $order) {
            return $this->responseNotFound("Vous n'avez pas de commande pour aujourd'hui.", "Commande non trouvée");
        }

        if(! $order->state->canTransitionTo(Completed::class)) {
            return $this->responseBadRequest("Vous ne pouvez pas récupérer votre commande.", "Plat déjà récupéré");
        }

        if($order->is_for_the_evening && now()->hour <= 18) {
            return $this->responseUnAuthorized("Vous ne pouvez pas récupérer votre commande de ce soir avant 18h.", "Non autorisé");
        }

        $order->markAsCompleted();


        return $this->responseSuccess("Bonjour {$accessCard->user->full_name}, votre commande de {$order->dish->name} a été marquée comme récupérée.", [
          'order' => $order
        ]);
    }



    //APi pour marquer les commandes confirmés en cas de reclamation

    public function markAsConfirmed(Request $request)
    {

        $request->validate([
          'order_identifier' => ['required', Rule::exists('orders', 'id')],
        ]);

        $order = Order::whereId($request->order_identifier)->first();
        $order->markAsConfirmed();


        return response()->json([
          'message' => "La commande a été marquée comme non consommée.",
          "success" => true,
          "order" => $order
        ]);
    }

    public function markAsBreakfastCompleted(Request $request)
    {
        $request->validate([
          'identifier' => ['required', Rule::exists('access_cards', 'identifier')],
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);
        $user = $accessCard->user->load('organization');

        if(! $user->canTakeBreakfast()) {
            return $this->responseBadRequest("Vous n'êtes pas autorisé à prendre le petit dejeuner.", "Non autorisé");
        }

        if($accessCard->quota_breakfast <= 0) {
            return $this->responseBadRequest("Vous n'avez plus de quota pour le petit dejeuner.", "Non autorisé");
        }


        $order = Order::where('user_id', $accessCard->user_id)
          ->withoutGlobalScopes()
          ->where('type', 'breakfast')
          ->where('state', 'completed')
          ->whereDate('created_at', today())
          ->first();

        if($order) {
            return $this->responseBadRequest("Vous avez déjà récupéré votre petit déjeuner.", "Déjà récupéré");
        }

        $order = Order::create([
          'user_id' => $accessCard->user_id,
          'type' => 'breakfast',
          'state' => 'completed',
          'payment_method_id' => $accessCard->payment_method_id,
          'access_card_id' => $accessCard->id,
          'is_decrement' => true,
        ]);

        $accessCard->decrement('quota_breakfast');

        return $this->responseSuccess("Bonjour {$accessCard->user->full_name}, votre commande de petit déjeuner a été marquée comme récupérée.", [
          'order' => $order
        ]);
    }
}