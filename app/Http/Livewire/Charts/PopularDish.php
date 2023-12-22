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
    public ?string $filter = 'today';


    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = "Top 5 des plats les plus commandés.";

    protected static string $color = 'primary';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $loadingIndicator = 'Loading...';





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
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Plat',
                    'data' => $values,
                    'color' => '#bf911b',
                ],
            ],
            'xaxis' => [
                'categories' => $keys,
                'labels' => [
                    'style' => [
                        'colors' => '#0d0c0c',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#bf911b',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
        ];
    }

    public static function getTopFiveDishes() : array
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
