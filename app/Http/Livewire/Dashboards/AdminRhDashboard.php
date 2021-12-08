<?php

namespace App\Http\Livewire\Dashboards;

use App\States\Order\Confirmed;
use Livewire\Component;

class AdminRhDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboards.admin-rh-dashboard',  [
            'active_access_card_count' => \App\Models\User::where('current_access_card_id', '!=', null)->count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'inactive_users_count' => \App\Models\User::where('is_active', false)->count(),
            'orders_confirmed_count' => \App\Models\Order::today()->whereState('state', Confirmed::class)->count(),
            'orders_completed_count' => \App\Models\Order::today()->whereState('state', Completed::class)->count(),
        ]);
    }
}
