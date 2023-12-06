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
        //     ->select('dish_id', DB::raw('WEEK(orders.created_at) as semaine'))
        //     ->groupBy('dish_id', 'menu_served_at')
        //     ->orderBy('menu_served_at', 'DESC', 'semaine')
        //     ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders')->get();

        // $dishByOrders = Order::select('dish_id',DB::raw('YEAR(created_at) as annee'),DB::raw('WEEK(created_at) as semaine'),DB::raw('count(*) as total_orders'))
        // ->groupBy('dish_id','annee','semaine')
        // -

        $dishByOrders = DB::table('orders')
            ->select('plat_id', DB::raw('WEEK(date_commande) as semaine'), DB::raw('COUNT(*) as nombre_commandes'))
            ->groupBy('plat_id', 'semaine')
            ->orderBy('semaine')
            ->get();


        $labels = [];
        $data = [];

        foreach ($dishByOrders as $orders) {
            $total = $orders->total_orders;
            $namePlat = $orders->dish->name;
            $week = $orders->semaine;

            // dd($this->convertirMonth($orders->semaine));
            $labels[] = $namePlat;
            $data[] = $total;
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    'borderWidth' => 1

                ],

            ],
            'labels' => $labels,
            'fill' => false

        ];

        $activeFilter = $this->filter;
    }

    private function convertirMonth($week)
    {
        return date("F", mktime(0, 0, 0, $week, 1));
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
