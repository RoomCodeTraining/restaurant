<?php

namespace App\Http\Controllers\API;

use App\Models\Menu;
use App\Models\Order;
use App\Models\AccessCard;
use Illuminate\Http\Request;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Actions\Order\CreateOrderAction;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrdersController extends Controller
{
    //use AuthorizesRequests;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateOrderAction $createOrderAction)
    {
        //$this->authorize('viewAny', Menu::class);

        $request->validate([
            'identifier' => ['required'],
            'dish_id' => ['required', Rule::exists('dishes', 'id')],
        ]);



        $todayMenu = Menu::with('dishes')->today()->first();
        $menuHasDish = $todayMenu->dishes->contains('id', $request->dish_id);
        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);

        if(!$accessCard){
          return response()->json([
              'message' => "Cette carte n'est associée à aucun compte dans le systeme.",
              'success' => false,
          ], Response::HTTP_NOT_FOUND);
      }

        if (! $menuHasDish) {
            throw ValidationException::withMessages([
                'dish_id' => ['Le plat choisi n\'est pas disponible pour aujourd\'hui.'],
            ]);
        }

        if ($accessCard->quota_lunch <= 0) {
            throw ValidationException::withMessages([
                'message' => 'Le quota de ce utilisateur est insuffisant.',
                'success' => false,
                'user' => $accessCard->user,
            ]);
        }

        $hasAlreadyOrdered = Order::query()
            ->whereHas('menu', fn ($query) => $query->whereDate('served_at', today()))
            ->whereBelongsTo($accessCard->user)
            ->whereState('state', [Confirmed::class, Completed::class])
            ->exists();

        if ($hasAlreadyOrdered) {
            return response()->json([
                'message' => "Cet utilisateur a déjà commandé un plat.",
                'success' => false,
                'user' => $accessCard->user,
            ], Response::HTTP_FORBIDDEN);
        }


        if(now()->hour >= config('cantine.order.locked_at')){
            $accessCard->decrement('quota_lunch');
        }

        $order = $createOrderAction->execute([
            'user_id' => $accessCard->user->id,
            'menu_id' => $todayMenu->id,
            'dish_id' => $request->dish_id,
        ]);

        ActivityHelper::createActivity(
          $order,
          'Création de commande',
          "$order->user->full_name vient de passer sa commande du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
        );

        return new OrderResource($order);
    }

    /**
     * Show completed orders for the authenticated user.
     * @return \Illuminate\Http\Response
     */

    public function orderCompleted(){
        $orders = Order::with('user', 'dish')->today()->whereState('state', completed::class)->get();
   
        return response()->json([
            'orders' => $orders,
            'success' => true,
            'message' => 'Commandes récupérées avec succès.'
        ]);
    }
}
