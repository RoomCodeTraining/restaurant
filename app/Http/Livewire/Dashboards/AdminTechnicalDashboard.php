<?php

namespace App\Http\Livewire\Dashboards;

use Livewire\Component;

class AdminTechnicalDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboards.admin-technical-dashboard',[
            'users_count' => \App\Models\User::count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'inactive_users_count' => \App\Models\User::where('is_active', false)->count(),
            "today_order" => \App\Models\Order::whereState('state', [Completed::class, Confirmed::class])->today()->with('dish')->where('user_id', auth()->id())->first(),
        ]);
    }
}
