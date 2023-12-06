<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;

class CategoryEmployeeConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            return [
                'datasets' => [
                    [
                        'label' => 'Blog posts created',
                        'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ];
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}