<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;

class HighConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Période de forte / faible consommation';
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
