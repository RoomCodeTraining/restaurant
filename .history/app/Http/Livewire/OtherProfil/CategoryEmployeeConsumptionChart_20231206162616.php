<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryEmployeeConsumptionChart extends ChartWidget
{

    protected static ?string $heading = 'Évolution mensuelle des consommations par type d\'utilisateurs  ';
    protected static ?string $maxHeight = '500px';

    protected function getData(): array
    {

        $ordersByUserCategory = DB::table('orders')
            ->join('users', 'orders.user_id', 'users.id')
            ->select('dish_id', 'users.user_type_id', DB::raw('MONTH(orders.created_at) as month'), DB::raw('COUNT(*) as total_orders'))
            ->groupBy('users.user_type_id')->get();

        dd($ordersByUserCategory);

        $mouvments = Order::where('user_id', '!=', null)
            ->get()->groupBy('user.userType.name');
        $mouvments = $mouvments->map(function ($mouvment) {
            return $mouvment->count();
        });

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
