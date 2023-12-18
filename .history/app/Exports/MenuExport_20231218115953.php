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

    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        $this->record;
        // $orders = Order::with('user', 'menu')->whereState('state', [Completed::class, Confirmed::class])
        //     ->latest();

        //dd($orders);
        // return $orders;
    }

    public function title(): string
    {
        return "Liste des utilisateurs";
    }

    public function headings(): array
    {
        return [
            "Matricule",
            "Nom",
            "Prénom",
            "Email",
            "Contact",

        ];
    }

    public function map($row): array
    {

        return [];
    }
}
