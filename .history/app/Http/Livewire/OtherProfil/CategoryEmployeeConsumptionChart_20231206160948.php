<?php

namespace App\Http\Livewire\OtherProfil;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryEmployeeConsumptionChart extends ChartWidget
{

    protected static ?string $heading = 'Évolution mensuelle des consommations par type d\'utilisateurs  ';
    protected static ?string $maxHeight = '500px';

    protected function getData(): array
    {

        $orders = DB::table('orders')->join('users', 'orders.user_id', 'users.id')
            ->select('dish_id', 'users.user_type_id', DB::raw('MONTH(orders.created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->groupBy('month', 'dish_id', 'users.user_type_id')
            ->distinct()
            ->selectRaw('dish_id, montht,users.user_type_id as categorie, COUNT(*) as total_orders')->get();
        //->havingRaw('COUNT(*)  < ?', [10])


        dd($orders);
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
