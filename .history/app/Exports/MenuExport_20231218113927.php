<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    use Exportable;

    public $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        $orders = Order::where('state', 'confirmed')
            ->with('user', 'menu')->whereState('state', [Completed::class, Confirmed::class])
            ->get();

        dd($orders);
        return $orders;
    }
}
