<?php

namespace App\Http\Livewire\Dashboards;

use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboards.admin-dashboard', [
            'users_count' => \App\Models\User::count(),
            'active_users_count' => \App\Models\User::where('is_active', true)->count(),
            'inactive_users_count' => \App\Models\User::where('is_active', false)->count(),
        ]);
    }
}
