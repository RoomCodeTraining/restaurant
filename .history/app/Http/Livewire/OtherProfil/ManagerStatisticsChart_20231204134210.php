<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;

class ManagerStatisticsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
