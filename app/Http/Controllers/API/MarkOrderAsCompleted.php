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


    //return response()->json(['message' => "Votre requête n'a pas pu être prise en compte.", 'success' => false], Response::HTTP_UNPROCESSABLE_ENTITY);
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

  public function markAsBreakfastCompleted(Request $request){
    $request->validate([
      'identifier' => ['required', Rule::exists('access_cards', 'identifier')],
    ]);
    $accessCard = AccessCard::with('user')->firstWhere('identifier', $request->identifier);

    if(! $accessCard->user->is_entitled_breakfast){
      return response()->json([
        'message' => "Vous n'êtes pas autorisé à prendre le petit déjeuner.",
        "success" => false,
        "user" => $accessCard->user
      ], Response::HTTP_NOT_FOUND);
    }

    if($accessCard->quota_breakfast <= 0){
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

    if($order){
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
