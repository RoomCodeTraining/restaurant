<?php

namespace App\Http\Livewire\OtherProfil;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class HighConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Période de forte / faible consommation';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {

        // $month =  Order::join('dishes', 'orders.dish_id', 'dishes.id')
        //     ->join('menus', 'orders.menu_id', 'menus.id')
        //     ->whereBetween('menus.served_at', [now()->startOfWeek(), now()->endOfWeek()])
        //     ->whereNotState('state', [Cancelled::class, Suspended::class])
        //     ->groupBy('dish_id', 'menu_served_at')
        //     ->orderBy('menu_served_at', 'DESC')
        //     ->selectRaw('dish_id, menus.served_at as menu_served_at, COUNT(*) as total_orders')->get();

        // dd($month);

        // $orders = Order::groupBy('dish_id')
        //     ->select('dish_id', DB::raw('count(*) as total_orders'))
        //     ->get();

        $orders = DB::table('orders')
            ->select('dish_id', DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->groupBy('month')
            ->havingRaw('COUNT(*)  < ?', [10])
            //->select('dish_id', DB::raw('COUNT(*) as total_orders'))
            // ->groupBy('dish_id', 'created_at')->orderBy('total_orders')

            ->get();

        dd($orders);

        $labels = [];
        $data = [];

        foreach ($orders as $order) {
            $mois = $order->month;
            $commande = $order->total_orders;

            $labels[] = $mois;
            $data[] = $commande;
        }

        dd(Carbon::parse($mois)->format('m'));

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            //'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
