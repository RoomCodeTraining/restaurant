<?php

namespace App\Http\Livewire\Dashboards;

use Livewire\Component;
use App\States\Order\Cancelled;
use App\States\Order\Confirmed;
use App\States\Order\Completed;

class AdminDashboard extends Component
{
    public function render()
    {



        return view('livewire.dashboards.admin-dashboard', [
            'users_count' => \App\Models\User::count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'inactive_users_count' => \App\Models\User::where('is_active', false)->count(),
            "today_order" => \App\Models\Order::where('state', 'confirmed')->today()->with('dish')->where('user_id', auth()->id())->first(),
        ]);
    }
}
