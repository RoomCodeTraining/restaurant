<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ManagerStatisticsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistiques ( Hebdommadaire ) des plats du plus ou moin consomés';
    protected static ?string $maxHeight = '300px';
    public ?string $filter = 'today';

    protected function getData(): array
    {
        // $dishByOrders =  Order::join('dishes', 'orders.dish_id', 'dishes.id')
        //     ->join('menus', 'orders.menu_id', 'menus.id')
        //     ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
        //     ->whereNotState('state', [Cancelled::class, Suspended::class])
        //     ->groupBy('dish_id', DB::raw('WEEK(created_at) as semaine', 'menu_served_at'))
        //     ->orderBy('menu_served_at', 'DESC')
        //     ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders')
        //     ->get();

        $dishByOrders =  Order::groupBy('dish_id', DB::raw('Date_format(data_imports.data_import) AS weeknumber'))
            ->select('dish_id', DB::raw('count(*) as total_orders'))

            ->get();

        dd($dishByOrders);

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

        $activeFilter = $this->filter;
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

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
