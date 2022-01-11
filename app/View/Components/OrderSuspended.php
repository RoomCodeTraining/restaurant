<?php

namespace App\View\Components;

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
        $hasAnOrderSuspended = \App\Models\Order::today()->whereState('state', Suspended::class)->first();
        return view('components.order-suspended', compact('hasAnOrderSuspended'));
    }
}
