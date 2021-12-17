<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\AccessCard;
use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Completed;
use Illuminate\Http\Request;
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
            'identifier' => ['required'],
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
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->access_card_identifier);
        $order = Order::with('dish')->today()->whereBelongsTo($accessCard->user)->first();

        if (! $order && $request->order_type == 'lunch') {
            return response()->json([
                "message" => "Vous n'avez pas de commande pour ce jour."
            ]);
        }

        if ($request->order_type == 'breakfast') {
            $accessCard->decrement('quota_breakfast');
        }

        if ($order && ! $order->state->canTransitionTo(Completed::class)) {
            return response()->json([
                "message" => "Vous avez déjà recupérer votre plat."
            ]);
        }

        if ($request->order_type == 'lunch' && $order && $order->state->canTransitionTo(Completed::class)) {
            $order->state->transitionTo(Completed::class);
        }

        return response()->json(['message' => "Votre commande de " . $order->dish->name . " a été confirmée." ]);
    }
}
