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
        $orders = Order::where('state', 'confirmed')
            ->withoutGlobalScope('lunch')->get();

        dd($orders);
        return $orders;
    }
}
