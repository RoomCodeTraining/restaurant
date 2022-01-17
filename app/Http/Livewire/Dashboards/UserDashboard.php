<?php

namespace App\Http\Livewire\Dashboards;

use App\Models\Order;
use Livewire\Component;
use App\States\Order\Cancelled;
use App\States\Order\Confirmed;
use App\States\Order\Completed;

class UserDashboard extends Component
{
   
    public function render()
    {
        return view('livewire.dashboards.user-dashboard', [
                "today_order" => Order::where('state', 'confirmed')->today()->with('dish')->where('user_id', auth()->id())->first(),
                'weekly_orders_count' => Order::weekly()->where('user_id', auth()->id())->count(),
                'monthly_order_count' => Order::monthly()->where('user_id', auth()->id())->count(),
          ]);
    }
}
