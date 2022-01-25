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
            'order_by_other' => $data['order_by_other'] ?? false
        ]);

        OrderCreated::dispatch($order);

        return $order;
    }
}
