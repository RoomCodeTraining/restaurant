<?php

namespace App\Http\Livewire\Charts;

use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class Lowconsumptionperiod extends ApexChartWidget
{
    protected static string $chartId = 'low-consumption-period';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = "Période de faible consommation";
    protected static string $color = 'primary';
    protected static ?string $pollingInterval = '10s';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getOptions(): array
    {
        $keys = array_keys(self::getLowConsumptionPeriod());
        $values = array_values(self::getLowConsumptionPeriod());
        // dd($this->getLowConsumptionPeriod());

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Plat',
                    'data' => $values,
                    'color' => '#E70D18',
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
                        'colors' => '#0d0c0c',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'tooltip' => [
                'x' => [
                    'format' => 'dd/MM/yy HH:mm',
                ],
            ],
        ];
    }

    public static function getLowConsumptionPeriod(): array
    {
        $orders = \App\Models\Order::whereState('state', [Completed::class, Confirmed::class])->get();
        $orders = $orders->groupBy(function ($item) {
            return $item->created_at->format('d/m/Y');
        });
        $orders = $orders->map(function ($item) {
            return $item->count();
        });
        // $orders = $orders->sort();
        $orders = $orders->take(10);

        return $orders->toArray();
    }

}
