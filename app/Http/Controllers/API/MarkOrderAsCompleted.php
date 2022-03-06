<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\AccessCard;
use Illuminate\Http\Request;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MarkOrderAsCompleted extends Controller
{
  use AuthorizesRequests;

  public function update(Request $request)
  {
    //  $this->authorize('viewAny', Menu::class);
    $request->validate([
      'order_type' => ['required', Rule::in(['breakfast', 'lunch'])],
      'access_card_identifier' => ['required']
    ]);




    $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->access_card_identifier);

    if (now()->hour < config('cantine.order.locked_at') && $request->order_type == 'lunch') {
      return response()->json([
        'message' => 'Vous ne pouvez pas retirer de repas avant ' . config('cantine.order.locked_at') . 'h'
      ], Response::HTTP_BAD_REQUEST);
    }

    if (!$accessCard) {
      return response()->json([
        'message' => "Cette carte n'est associée à aucun compte dans le systeme.",
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
      ], 201);
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
    if (!$order && $request->order_type == 'lunch') {
      return response()->json([
        "message" => 'Vous n\'avez pas de commande pour le déjeuner du jour. Veuillez vous rendre chez l\'operateur cantine pour passer une commande exceptionnelle.',
        "success" => false,
      ], Response::HTTP_NOT_FOUND);
    }

    if ($request->order_type === 'breakfast' && $accessCard->user->created_at->isToday()) {
      return response()->json([
        "message" => "Votre compte a été crée aujourd'hui. Le pointage petit dejeuner sera activé dans 24H.",
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

      ActivityHelper::createActivity(
          $order,
          'Retrait de son petit dejeuner du jour',
          "$order->user->full_name vient de retirer sa commande petit dejeuner du jour",
        );

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
    ActivityHelper::createActivity(
      $order,
      'Retrait de son déjeuner du ' . $order->menu->served_at->format('d/m/Y'),
      "$order->user->full_name vient de retirer sa commande dejeuner du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
    );

    if ($request->order_type === 'lunch') {
      $order->markAsCompleted();
      return response()->json([
        'message' => "La commande de {$order->dish->name} effectuée par Mr/Mme {$accessCard->user->full_name} a été récupérée.",
        "success" => true,
        "user" => $accessCard->user
      ]);
    }

    return response()->json(['message' => "Votre requête n'a pas pu être prise en compte.", 'success' => false], Response::HTTP_UNPROCESSABLE_ENTITY);
  }


  public function markAsConfirmed(Request $request)
  {

    $request->validate([
      'order_identifier' => ['required', Rule::exists('orders', 'id')],
    ]);

    $order = Order::whereId($request->order_identifier)->first();
    $order->markAsConfirmed();


    ActivityHelper::createActivity(
      $order,
      'Annulation de la commande du ' . $order->menu->served_at->format('d/m/Y') .$order->user->full_name,
      "La commande de $order->user->full_name du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'). ' a été annulée.',
    );

    return response()->json([
      'message' => "La commande a été marquée comme non consommée.",
      "success" => true,
      "order" => $order
    ]);
  }
}
