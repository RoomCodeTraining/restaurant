<?php

namespace App\Http\Livewire\Dashboards;

use App\States\Order\Confirmed;
use Livewire\Component;

class AdminCantineDashboard extends Component
{
    public $menu;
    public $main_dish_count;
    public $second_dish_count;

    public function mount()
    {

        $this->menu = \App\Models\Menu::today()->first();
        if ($this->menu) {
            $this->main_dish_count = \App\Models\Order::today()->where(['dish_id' => $this->menu->main_dish->id, 'menu_id' => $this->menu->id])->count();
            $this->second_dish_count = \App\Models\Order::today()->where(['dish_id' => $this->menu->second_dish->id, 'menu_id' => $this->menu->id])->count();
        }
    }

    public function render()
    {
        return view('livewire.dashboards.admin-cantine-dashboard', [
            'today_orders_count' => \App\Models\Order::today()->whereState('state', Confirmed::class)->count(),
            'orders_completed_count' => \App\Models\Order::whereState('state', Completed::class)->count(),
            'orders_cancelled_count' => \App\Models\Order::whereState('state', Cancelled::class)->count(),
        ]);
    }
}
