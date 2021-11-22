<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\AccessCard;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
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
            'menuId' => ['required', Rule::exists('menus', 'id')],
            'dishId' => ['required', Rule::exists('dishes', 'id')],
        ]);

        $accessCard = AccessCard::with('user')->whereIdentifier($request->identifier)->first();

        if (!$accessCard->quota_breakfast > 0) {
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
            if (!$result->isEmpty()) {
                return response()->json(['msg' => 'Vous avez deja une commande pour ce jour!']);
            }
        }

        return new OrderResource($order);
    }

    public function update(Request $request)
    {
        $request->validate([
            'identifier' => ['required']
        ]);

        $today = Carbon::parse(now())->format('d/m/Y');
        $accessCard = AccessCard::with('user')->whereIdentifier($request->identifier)->first();
        $orders = $accessCard->user->orders;

        $todayOrder = $orders->map(function ($order) use ($today) {
            if ($order->menu->served_at == $today) {
                return $order;
            }
        });

        if ($todayOrder->isEmpty()) {
            return response()->json(['msg' => "Desolé vous n'avez pas une commande du jour!"]);
        } elseif ($todayOrder[0]->is_completed) {
            return response()->json(['msg' => 'Oups le plat a été retiré']);
        }

        $todayOrder[0]->update(['is_completed' => true]);
        return response()->json(['msg' => "Le plat commandé par Mr/Mme ". $todayOrder[0]->user->full_name." est ". $todayOrder[0]->dish->name]);
    }
}
