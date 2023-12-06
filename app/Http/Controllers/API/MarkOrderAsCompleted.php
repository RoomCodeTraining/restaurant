<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AccessCard;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class MarkOrderAsCompleted extends Controller
{
    use AuthorizesRequests;

    public function markAsLunchCompleted(Request $request)
    {
        $request->validate([
            'identifier' => ['required', Rule::exists('access_cards', 'identifier')],
        ]);

        $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);
        $order = Order::today()->firstWhere('user_id', $accessCard->user_id);

        if(! $order) {
            return response()->json([
                'message' => "Vous n'avez pas de commande pour aujourd'hui.",
                "success" => false,
                "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        if($order->state === 'completed') {
            return response()->json([
                'message' => "Vous avez déjà récupéré votre commande de ce jour.",
                "success" => false,
                "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        if($order->is_for_the_evening && now()->hour <= 18) {
            return response()->json([
                'message' => "Vous ne pouvez pas récupérer votre commande de ce soir avant 18h.",
                "success" => false,
                "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        $order->markAsCompleted();

        return response()->json([
            'message' => "Bonjour {$accessCard->user->first_name}, votre commande de {$order->dish->name} a été marquée comme récupérée.",
            "success" => true,
            "order" => $order
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

        if(! $accessCard->user->is_entitled_breakfast) {
            return response()->json([
              'message' => "Vous n'êtes pas autorisé à prendre le petit déjeuner.",
              "success" => false,
              "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        if($accessCard->quota_breakfast <= 0) {
            return response()->json([
              'message' => "Vous n'avez plus de petit déjeuner.",
              "success" => false,
              "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
        }

        // get the today breakfast order

        $order = Order::where('user_id', $accessCard->user_id)
          ->withoutGlobalScopes()
          ->where('type', 'breakfast')
          ->where('state', 'completed')
          ->whereDate('created_at', today())
          ->first();

        if($order) {
            return response()->json([
              'message' => "Vous avez déjà récupéré votre petit déjeuner de ce jour.",
              "success" => false,
              "user" => $accessCard->user
            ], Response::HTTP_NOT_FOUND);
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

        return response()->json([
          'message' => "Mr/Mme {$accessCard->user->full_name} a recupéré son petit déjeuner.",
          "success" => true,
          //"user" => new UserResource($accessCard->user),
        ]);
    }
}