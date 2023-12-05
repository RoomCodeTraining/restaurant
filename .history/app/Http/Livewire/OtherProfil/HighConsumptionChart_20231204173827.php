<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;

class HighConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Période de forte / faible consommation';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {

        $monthDish =  Order::groupBy('dish_id', DB::raw('MONTH(created_at) as mois'))
            ->select('dish_id', DB::raw('count(*) as total_orders'))->orderBy('total_orders', 'desc')->get();
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
