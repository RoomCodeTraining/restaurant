<?php

namespace App\Http\Controllers\API;

use App\Actions\Order\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\AccessCardResource;
use App\Http\Resources\OrderResource;
use App\Models\AccessCard;
use App\Models\Menu;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Gesion des commandes
 *
 * Endpoints pour la gestion des commandes
 * @authenticated
 * APIs for managing orders
 */
class OrdersController extends Controller
{
    /**
     * Effectuer une commande pour un tiers
     *
     * Cette endpoint permet de passer une commande pour un tiers
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction)
    {
        $todayMenu = Menu::with('dishes')
            ->whereDate('served_at', today())
            ->first();
        $menuHasDish = $todayMenu->dishes->contains('id', $request->dish_id);
        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);
        // dd($menuHasDish);
        if (! $accessCard) {
            return response()->json(
                [
                    'message' => "Cette carte n'est associée à aucun compte dans le systeme.",
                    'success' => false,
                ],
                Response::HTTP_NOT_FOUND,
            );
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
            return response()->json(
                [
                    'message' => 'Cet utilisateur a déjà commandé un plat.',
                    'success' => false,
                    'user' => $accessCard->user,
                ],
                Response::HTTP_FORBIDDEN,
            );
        }

        $order = $createOrderAction->execute([
            'user_id' => $accessCard->user->id,
            'menu_id' => $todayMenu->id,
            'dish_id' => $request->dish_id,
        ]);

        /*
         * Quand il s'agit d'une commande exceptionnelle et que l'heure est superieur a 10h. Il faut decreementer le quota
         */

        if ((int) config('cantine.order.locket_at') <= now()->hour) {
            $this->canChargeUser($order, $accessCard);
        }

        activity()
            ->causedBy(Auth()->user())
            ->performedOn($order)
            ->event('Mr/Mme ' . auth()->user()->full_name . ' vient de passer une commande exceptionnelle du ' . $order->menu->served_at->format('d-m-Y') . ' pour ' . $order->user->full_name)
            ->log('Creation de commande pour tiers');

        return new OrderResource($order);
    }

    public function orderCompleted()
    {
        $orders = Order::with('user', 'dish')
            ->today()
            ->whereState('state', completed::class)
            ->get();

        return response()->json([
            'orders' => $orders,
            'success' => true,
            'message' => 'Commandes récupérées avec succès.',
        ]);
    }

    public function canChargeUser(Order $order, AccessCard $accessCard)
    {
        $order->update(['is_exceptional' => true, 'is_decrement' => true]);
        $accessCard->decrement('quota_lunch');

        return 1;
    }

    /**
     * Badger une commande comme consommée
     *
     * Cette endpoint permet de badger une commande comme consommée(Petit dejeuner)
     * @return \Illuminate\Http\Response
     */
    public function createBreakfastOrder(AccessCard $accessCard)
    {
        if ($accessCard->quota_breakfast <= 0) {
            return response()->json(
                [
                    'message' => 'Votre quota petit dejeuner est epuisé. Merci de recharger votre compte.',
                    'success' => false,
                    'user' => new AccessCardResource($accessCard),
                ],
                Response::HTTP_FORBIDDEN,
            );
        }

        $order = Order::create([
            'user_id' => $accessCard->user->id,
            'type' => 'breakfast',
            'payment_method_id' => $accessCard->payment_method_id,
        ]);

        return response()->json(
            [
                'message' => 'Commande petit dejeuner retirée avec succès.',
                'success' => true,
                'order' => new OrderResource($order),
            ],
            Response::HTTP_CREATED,
        );
    }
}