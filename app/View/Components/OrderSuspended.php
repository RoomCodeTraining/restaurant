<?php

namespace App\View\Components;

use App\States\Order\Completed;
use App\States\Order\Suspended;
use Illuminate\View\Component;

class OrderSuspended extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $order_is_suspended = false;

        // Recuperation des commandes de la semaine qui on été suspendues
        $count_orders_suspended = \App\Models\Order::join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now(), now()->endOfWeek()])
            ->whereState('state', Suspended::class)->whereUserId(auth()->id())->count();

 
        $order_is_suspended = $count_orders_suspended > 0 ? true : false; // $order_is_suspended -> true si les commandes suspendues sont superieurs a 0
        return view('components.order-suspended', compact('order_is_suspended'));
    }
}
