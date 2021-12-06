<?php

namespace App\Http\Livewire\Dashboards;

use Livewire\Component;

class AdminRhDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboards.admin-rh-dashboard',  [
            'active_access_card_count' => \App\Models\User::where('current_access_card_id', '!=', null)->count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'inactive_users_count' => \App\Models\User::where('is_active', false)->count(),
        ]);
    }
}
