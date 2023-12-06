<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use App\Models\UserType;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryEmployeeConsumptionChart extends ChartWidget
{

    protected static ?string $heading = 'Évolution mensuelle des consommateurs par categories  ';
    protected static ?string $maxHeight = '500px';

    protected function getData(): array
    {

        $ordersByUserCategory = Order::where('user_id', '!=', null)
            ->get()->groupBy('user.userType.name');

        $ordersByCategoryUser = $ordersByUserCategory->map(function ($userByCategory) {
            return $userByCategory->count();
        });

        //dd($ordersByCategoryUser);

        return [

            'datasets' => [
                [
                    'label' => 'Nbr(s) de consommateurs',
                    'data' => $ordersByCategoryUser->toArray(),
                ],
            ],
            'labels' =>  UserType::all()->pluck('name')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
