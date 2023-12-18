<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\States\Order\Completed;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    public function collection()
    {
        $orders = Order::query()
            ->withoutGlobalScope('lunch')
            ->whereType('breakfast')
            ->with('user', 'menu')
            ->whereState('state', Completed::class)
            //->whereBetween('created_at',  DateTimeHelper::inThePeriod($this->period))
            ->get();

        dd($orders);
        return $orders;
    }
}
