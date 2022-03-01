<?php

namespace App\Actions\Order;

use App\Models\Role;
use App\Models\Order;
use App\Events\OrderCreated;
use App\Support\ActivityHelper;

class CreateOrderAction
{
    public function execute(array $data): Order
    {

        $order = Order::create([
            'user_id' => $data['user_id'],
            'dish_id' => $data['dish_id'],
            'menu_id' => $data['menu_id'],
        ]);


     
     /*
      ActivityHelper::createActivity(
        Auth()->user(),
        "Création de sa commande du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
        "$order->user->full_name vient de passer sa commande du " . \Carbon\Carbon::parse($order->menu->served_at)->format('d-m-Y'),
      );*/

        OrderCreated::dispatch($order);

        return $order;
    }
}
