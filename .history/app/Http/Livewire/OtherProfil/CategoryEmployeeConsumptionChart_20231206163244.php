<?php

namespace App\Http\Livewire\OtherProfil;

use App\Models\Order;
use App\Models\UserType;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryEmployeeConsumptionChart extends ChartWidget
{

    protected static ?string $heading = 'Évolution mensuelle des consommations par type d\'utilisateurs  ';
    protected static ?string $maxHeight = '500px';

    protected function getData(): array
    {

        // $ordersByUserCategory = DB::table('orders')
        //     ->join('users', 'orders.user_id', 'users.id')
        //     ->select('dish_id', 'users.user_type_id', DB::raw('MONTH(orders.created_at) as month'), DB::raw('COUNT(*) as total_orders'))
        //     ->groupBy('users.user_type_id')->get();

        // dd($ordersByUserCategory);

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

    protected function getType(): string
    {
        return 'line';
    }
}
