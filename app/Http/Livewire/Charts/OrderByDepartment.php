<?php

namespace App\Http\Livewire\Charts;

use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OrderByDepartment extends ApexChartWidget
{
    /**
    * Chart Id
    *
    * @var string
    */
    protected static string $chartId = 'order-by-department';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Top 15 des départements qui ont le plus consommé';
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
        $keys = array_keys(self::countOrdersByDepartment());
        $values = array_values(self::countOrdersByDepartment());
        // dd($this->countOrdersByDepartment());

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
            'grid' => [
                'borderColor' => '#e7e7e7',
                'row' => [
                    'colors' => ['#f3f3f3', 'transparent'],
                    'opacity' => 0.5,
                ],
            ],
        ];
    }

    private static function countOrdersByDepartment() : array
    {

        $departments = \App\Models\Department::with('users')->get();
        $orders = \App\Models\Order::with('user')
            ->whereState('state', [Completed::class, Confirmed::class])
            ->get()->groupBy(function ($order) {
                return $order->user?->department->name;
            })->map(function ($orders) {
                return $orders->count();
            })->toArray();

        $orders = array_slice($orders, 0, 15, true);

        return $orders;
    }

}
