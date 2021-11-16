<?php

namespace App\Actions\Order;

use App\Events\OrderCreated;
use App\Models\Order;

class CreateOrderAction
{
    public function execute(array $data): Order
    {
        $order = Order::create([
            'user_id' => $data['user_id'],
            'dish_id' => $data['dish_id'],
            'menu_id' => $data['menu_id'],
        ]);

        OrderCreated::dispatch($order);

        return $order;
    }
}
