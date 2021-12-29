<?php

namespace App\Http\Controllers\API;

use App\Actions\Order\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\AccessCard;
use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateOrderAction $createOrderAction)
    {
        $this->authorize('viewAny', Menu::class);

        $request->validate([
            'identifier' => ['required', Rule::exists('access_cards', 'identifier')],
            'dish_id' => ['required', Rule::exists('dishes', 'id')],
        ]);

        $todayMenu = Menu::with('dishes')->today()->first();
        $menuHasDish = $todayMenu->dishes->contains('id', $request->dish_id);

        if (! $menuHasDish) {
            throw ValidationException::withMessages([
                'dish_id' => ['Le plat choisi n\'est pas disponible pour aujourd\'hui.'],
            ]);
        }

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);

        if ($accessCard->quota_lunch <= 0) {
            throw ValidationException::withMessages([
                'message' => 'Le quota de ce utilisateur est insuffisant quota est insuffisant.',
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

        $order = $createOrderAction->execute([
            'user_id' => $accessCard->user->id,
            'menu_id' => $todayMenu->id,
            'dish_id' => $request->dish_id,
        ]);

        return new OrderResource($order);
    }
}
