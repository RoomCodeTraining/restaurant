<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\AccessCard;
use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Completed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrdersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'identifier' => ['required', Rule::exists('users', 'identifier')],
            'menu_id' => ['required', Rule::exists('menus', 'id')],
            'dish_id' => ['required', Rule::exists('dishes', 'id')],
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);

        if ($accessCard->quota_lunch <= 0) {
            return response()->json(['msg' => 'Veuillez recharger votre cota de déjeuner']);
        }

        if ($accessCard->user->orders->isEmpty()) {
            $order = Order::create([
                'menu_id' => $request->menuId,
                'dish_id' => $request->dishId,
                'user_id' => $accessCard->user->id,
            ]);
        } else {
            $orders = $accessCard->user->orders;
            $menu = Menu::whereId($request->menuId)->first();

            $result = $orders->map(function ($order) use ($menu) {
                if ($order->menu->served_at == $menu->served_at) {
                    return $order;
                }
            });

            if (! $result->isEmpty()) {
                return response()->json(['msg' => 'Vous avez deja une commande pour ce jour!']);
            }
        }

        return new OrderResource($order);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'order_type' => ['required', Rule::in(['breakfast', 'lunch'])],
            'access_card_identifier' => ['required', Rule::exists('access_cards', 'identifier')],
        ], [
            'access_card_identifier.exists' => "Ce utilisateur n'a pas de carte associée à son compte."
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->access_card_identifier);
        $order = Order::with('dish', 'menu')->today()->whereBelongsTo($accessCard->user)->first();

        /**
         * Lorsque le quota de l'utilisateur est insuffisant.
         */
        if ($accessCard->{'quota_' . $request->order_type} <= 0) {
            return response()->json([
                'message' => "Votre quota est insuffisant.",
                "success" => false,
                "user" => $accessCard->user,
            ]);
        }

        /**
         * Lorsque l'utilisateur a déjà récupéré son plat.
         */
        $hasAlreadyEaten = $accessCard->user->actions()
            ->where('event', 'decrement_quota_'.$request->order_type)
            ->whereDate('created_at', now())
            ->exists();

        if ($hasAlreadyEaten) {
            $item = $request->order_type === 'lunch' ? 'déjeuner' : 'petit déjeuner';

            return response()->json([
                "message" => "Mr/Mme {$accessCard->user->full_name} a déjà recupéré son {$item}.",
                "success" => false,
                "user" => $accessCard->user
            ]);
        }

        /**
         * Lorsque l'utilisateur récupéres sont plat, applicable seulement au petit déjeuner.
         */
        if ($request->order_type === 'breakfast') {
            DB::transaction(function () use ($request, $accessCard) {
                $accessCard->decrement('quota_breakfast');

                activity()
                    ->event('decrement_quota_breakfast')
                    ->causedBy($accessCard->user)
                    ->performedOn($accessCard)
                    ->withProperties(['quota_breakfast' => $accessCard->fresh()->quota_breakfast])
                    ->log($request->order_type);
            });

            return response()->json([
                'message' => "Mr/Mme {$accessCard->user->full_name} a recupéré son petit déjeuner.",
                "success" => true,
                "user" => $accessCard->user,
            ]);
        }

        /**
         * Lorsque l'utilisateur n'a pas fait de commande pour le jour en cours.
         */
        if ($request->order_type === 'lunch' && ! $order) {
            return response()->json([
                "message" => "Vous n'avez pas de commande pour ce jour.",
                "success" => false,
                "user" => $accessCard->user
            ]);
        }

        /**
         * Lorsque l'utilisateur récupère son plat, applicable seulement au déjeuner.
         */
        if ($request->order_type === 'lunch' && $order->state->canTransitionTo(Completed::class)) {
            $order->markAsCompleted();

            activity()
                ->event('decrement_quota_lunch')
                ->causedBy($order->user->accessCard->user)
                ->performedOn($order->user->accessCard)
                ->withProperties(['quota_lunch' => $order->user->accessCard->fresh()->quota_lunch, 'menu_id' => $order->menu->id])
                ->log('lunch');

            return response()->json([
                'message' => "La commande de {$order->dish->name} effectuée par Mr/Mme {$accessCard->user->full_name} a été récupérée.",
                "success" => true,
                "user" => $accessCard->user
            ]);
        }

        return response()->json([ 'message' => "Votre requête n'a pas pu être prise en compte.", 'success' => false ]);
    }
}
