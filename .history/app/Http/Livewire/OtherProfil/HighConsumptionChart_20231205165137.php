<?php

namespace App\Http\Livewire\OtherProfil;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class HighConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Évolution mensuelle des périodes de forte / faible consommation ';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        // $orders = DB::table('orders')
        //     ->select('dish_id', DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_orders'))
        //     ->groupBy('month')
        //     ->havingRaw('COUNT(*)  < ?', [10])
        //     ->get();

        $orders = Order::query()->where('dish_id', '!=', null)
            ->whereNotState('state', [Cancelled::class, Suspended::class])
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->get()->groupBy(function ($order) {
                return $order->created_at->format('M');
            });

        return [
            'datasets' => [
                [
                    'label' => 'Nombre de consommateurs pour cet mois ',
                    'data' => $orders->map(function ($order) {
                        return $order->count() . '';
                    })->toArray(),
                ],
            ],

            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'fill' => false,
        ];
    }

    private function convertirMonth($mois)
    {
        return date("F", mktime(0, 0, 0, $mois, 1));
    }

    protected function getType(): string
    {
        return 'line';
    }
}
