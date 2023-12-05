<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ManagerStatisticsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistiques (Hebdommadaire )  des plats du plus ou moin consomés';
    protected static ?string $maxHeight = '300px';

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
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],

                ],

            ],
            'labels' => $labels,

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];
}
