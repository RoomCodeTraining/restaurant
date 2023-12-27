<?php

namespace App\Http\Livewire\Dashboards;

use Livewire\Component;
use App\States\Order\Cancelled;
use App\States\Order\Completed;

use App\States\Order\Confirmed;
use App\States\Order\Suspended;

class AdminCantineDashboard extends Component
{
    public $menu;
    public $main_dish_count;
    public $second_dish_count;

    public function mount()
    {

        $this->menu = \App\Models\Menu::today()->first();
        if ($this->menu) {
            $this->main_dish_count = \App\Models\Order::whereNotState('state', Cancelled::class)->today()->where(['dish_id' => $this->menu->main_dish->id, 'menu_id' => $this->menu->id])->count();

            if ($this->menu->second_dish) {
                $this->second_dish_count = \App\Models\Order::whereNotState('state', Cancelled::class)->today()->where(['dish_id' => $this->menu->second_dish->id, 'menu_id' => $this->menu->id])->count();
            }
        }
    }

    public function render()
    {
        dd(\App\Models\MenuSpecal::today()->first());
        return view('livewire.dashboards.admin-cantine-dashboard', [
            'today_orders_count' => \App\Models\Order::today()->whereNotState('state', [Cancelled::class, Suspended::class])->count(),
            'orders_completed_count' => \App\Models\Order::today()->whereState('state', Completed::class)->count(),
            'orders_cancelled_count' => \App\Models\Order::today()->whereState('state',  Cancelled::class)->count(),
            'sun_orders_count' => \App\Models\Order::today()->where('is_for_the_evening', false)->whereNotState('state', [Cancelled::class, Suspended::class])->count(),
            'journey_orders_count' => \App\Models\Order::today()->where('is_for_the_evening', true)->whereNotState('state', [Cancelled::class, Suspended::class])->count(),
            'dish_of_day' => \App\Models\MenuSpecial::today()->first(),
        ]);
    }
}
