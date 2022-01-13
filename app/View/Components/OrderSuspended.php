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
        $hasAnOrderSuspended = \App\Models\Order::orderByDesc('created_at')->withTrashed()->whereUserId(auth()->user()->id)->today()->first();


       if ($hasAnOrderSuspended && $hasAnOrderSuspended->state == Suspended::class) {
           $order_is_suspended = true;
       }
        return view('components.order-suspended', compact('order_is_suspended'));
    }
}
