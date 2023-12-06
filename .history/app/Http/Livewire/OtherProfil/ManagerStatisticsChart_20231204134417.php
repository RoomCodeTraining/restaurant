<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;

class ManagerStatisticsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $dishByOrders =  Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders');
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
