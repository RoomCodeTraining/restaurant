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
        if(auth()->user()->hasRole(Role::USER)){
            $is_for_the_evening = auth()->user()->order_for_evening;
        }

        $order = Order::create([
            'user_id' => $data['user_id'],
            'dish_id' => $data['dish_id'],
            'menu_id' => $data['menu_id'],
            'is_for_the_evening' => $is_for_the_evening ?? false,
        ]);

        OrderCreated::dispatch($order);
        return $order;
    }
}
