<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ManagerStatisticsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistiques Hebdommadaire/Mensuel des plats du plus ou moin consomés';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $dishByOrders =  Order::join('dishes', 'orders.dish_id', 'dishes.id')
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->groupBy('dish_id', 'menu_served_at')
            ->orderBy('menu_served_at', 'DESC')
            ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders')
            ->get();

        $labels = [];
        $data = [];

        foreach ($dishByOrders as $orders) {
            $total = $orders->total_orders;
            $namePlat = $orders->dish->name;

            $labels[] = $namePlat;
            $data[] = $total;
        }

        return [
            'datasets' => [
                [
                    // 'label' => 'Blog posts created',
                    'data' => $data,
                    'backgroundColor' => ['#48b091', '#80202e'],
                ],

            ],
            'labels' => $labels,

            'fill' => true,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}