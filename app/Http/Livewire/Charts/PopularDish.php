<?php

namespace App\Http\Livewire\Charts;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PopularDish extends ApexChartWidget
{
    /**
        * Chart Id
        *
        * @var string
        */
    protected static string $chartId = 'popular-dish';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Top 5 des plats les plus commandés';

    protected static string $color = 'primary';



    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $keys = array_column(self::getTopFiveDishes(), 'name');
        $values = array_column(self::getTopFiveDishes(), 'quantity');

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 200,
            ],
            'series' => [
                [
                    'name' => 'Plat',
                    'data' => $values,
                ],
            ],
            'xaxis' => [
                'categories' => $keys,
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
        ];
    }

    private static function getTopFiveDishes() : array
    {
        return  Order::select('dish_id', DB::raw('count(*) as total'))
            ->groupBy('dish_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'name' => $order->dish->name,
                    'quantity' => $order->total,
                ];
            })
            ->toArray();
    }
}
