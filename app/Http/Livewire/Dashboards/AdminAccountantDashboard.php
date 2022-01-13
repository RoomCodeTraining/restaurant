<?php

namespace App\Http\Livewire\Dashboards;

use App\States\Order\Completed;
use Livewire\Component;

class AdminAccountantDashboard extends Component
{
    public function render()
    {
        $orders = \App\Models\Order::monthly()->whereState('state', Completed::class)->get();

        $guest_monthly_orders_count = $orders->map(function ($order) {
            if ($order->user->user_type_id == 3) {
                return $order;
            }
        });

        $ciprel_agent_monthly_orders_count = $orders->map(function ($order) {
            if ($order->user->user_type_id === 1) {
                return $order;
            }
        });

        $intern_monthly_orders_count = $orders->map(function ($order) {
            if ($order->user->user_type_id == 4) {
                return $order;
            }
        });

        $others_monthly_orders_count = $orders->map(function ($order) {
            if ($order->user->user_type_id ==  2) {
                return $order;
            }
        });


        $monthly_orders_completed = \App\Models\Order::monthly()->whereState('state', Completed::class)->count();
        $monthly_orders_cancelled = \App\Models\Order::monthly()->whereState('state', Cancelled::class)->count();
        $monthly_orders_suspended = \App\Models\Order::monthly()->whereState('state', Suspended::class)->count();
        $today_order = \App\Models\Order::today()->with('dish')->where('user_id', auth()->id())->first();



        $data = compact('today_order', 'others_monthly_orders_count', 'intern_monthly_orders_count', 'guest_monthly_orders_count', 'ciprel_agent_monthly_orders_count', 'monthly_orders_completed', 'monthly_orders_cancelled', 'monthly_orders_suspended');


        return view('livewire.dashboards.admin-accountant-dashboard', $data);
    }
}
