<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AccessCard;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class MarkOrderAsCompleted extends Controller
{
    use AuthorizesRequests;

    public function update(Request $request)
    {
        //$this->authorize('viewAny', Menu::class);
     
        $request->validate([
            'order_type' => ['required', Rule::in(['breakfast', 'lunch'])],
            'access_card_identifier' => ['required', Rule::exists('access_cards', 'identifier')],
        ], [
            'access_card_identifier.exists' => "Ce utilisateur n'a pas de carte associée à son compte."
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->access_card_identifier);

        if (! $accessCard) {
            return response()->json([
                'message' => "Cette carte n'est associée à aucun compte.",
            ], Response::HTTP_NOT_FOUND);
        }

        /**
         * Lorsque le quota de l'utilisateur est insuffisant.
         */
        if ($accessCard->{'quota_' . $request->order_type} <= 0) {
            return response()->json([
                'message' => "Votre quota est insuffisant.",
                "success" => false,
                "user" => $accessCard->user,
            ], Response::HTTP_FORBIDDEN);
        }

        /**
         * Lorsque l'utilisateur a déjà récupéré son plat.
         */
        $hasAlreadyEaten = Order::query()
            ->withoutGlobalScopes()
            ->whereBelongsTo($accessCard->user)
            ->where('type', $request->order_type)
            ->when($request->order_type == 'lunch', function ($query) {
                return $query->whereHas('menu', fn ($query) => $query->whereDate('served_at', today()));
            })
            ->when($request->order_type == 'breakfast', fn ($query) => $query->whereDate('created_at', today()))
            ->whereState('state', Completed::class)
            ->exists();

        if ($hasAlreadyEaten) {
            $item = $request->order_type === 'lunch' ? 'déjeuner' : 'petit déjeuner';

            return response()->json([
                "message" => "Mr/Mme {$accessCard->user->full_name} a déjà recupéré son {$item}.",
                "success" => false,
                "user" => $accessCard->user
            ], Response::HTTP_FORBIDDEN);
        }

        $order = Order::query()
            ->withoutGlobalScopes()
            ->when($request->order_type == 'lunch', function ($query) {
                return $query->whereHas('menu', fn ($query) => $query->whereDate('served_at', today()));
            })
            ->when($request->order_type == 'breakfast', fn ($query) => $query->whereDate('created_at', today()))
            ->whereBelongsTo($accessCard->user)
            ->where('type', $request->order_type)
            ->whereState('state', Confirmed::class)
            ->first();

        /**
         * Lorsque l'utilisateur n'a pas fait de commande pour le jour en cours.
         */
        if (! $order) {
            return response()->json([
                "message" => "Vous n'avez pas de commande pour ce jour.",
                "success" => false,
                "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        /**
         * Lorsque l'utilisateur récupéres sont plat, applicable seulement au petit déjeuner.
         */
        if ($request->order_type === 'breakfast') {
            DB::transaction(function () use ($accessCard, $order) {
                $order->markAsCompleted();

                $order->update([
                    'payment_method_id' => $accessCard->payment_method_id,
                    'access_card_id' => $accessCard->id,
                ]);

                $accessCard->decrement('quota_breakfast');
            });

            return response()->json([
                'message' => "Mr/Mme {$accessCard->user->full_name} a recupéré son petit déjeuner.",
                "success" => true,
                "user" => $accessCard->user,
            ]);
        }

        /**
         * Lorsque l'utilisateur récupère son plat, applicable seulement au déjeuner.
         */
        if ($request->order_type === 'lunch') {
            $order->markAsCompleted();

            return response()->json([
                'message' => "La commande de {$order->dish->name} effectuée par Mr/Mme {$accessCard->user->full_name} a été récupérée.",
                "success" => true,
                "user" => $accessCard->user
            ]);
        }

        return response()->json([ 'message' => "Votre requête n'a pas pu être prise en compte.", 'success' => false ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
