<?php

namespace App\Exports;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\BillingHelper;
use App\Support\DateTimeHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $period;
    protected $state;

    protected $record;

    public function __construct($record)
    {
        $this->record = $record;

        dd($this->record);
        // $this->period = $period;
        // $this->state = $state;
    }

    public function collection()
    {

        return $this->record;
        // return Order::query()
        //     ->join('users', 'orders.user_id', 'users.id')
        //     ->join('user_types', 'users.user_type_id', 'user_types.id')
        //     ->join('menus', 'orders.menu_id', 'menus.id')
        //     ->with('menu', 'user.role', 'user.department', 'user.employeeStatus', 'user.userType', 'user.accessCard')
        //     ->unless($this->state, fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
        //     ->when($this->state, fn ($query) => $query->whereState('state', $this->state))
        //     ->whereBetween('menus.served_at', DateTimeHelper::inThePeriod($this->period))
        //     ->get()->groupBy('user_id')
        //     ->map(fn ($row) => $row->groupBy(fn ($item) =>  $item->menu->served_at->format('Y-m-d')))
        //     ->flatten(1);
    }

    public function title(): string
    {
        [$start, $end] = DateTimeHelper::inThePeriod($this->period);

        switch ($this->state) {
            case 'confirmed':
                $state =  'Commande non consommé';
                break;
            case 'completed':
                $state = 'Commande consommé';
                break;
            default:
                $state = "Toutes les commandes";
                break;
        }


        return " $state du " . $start->format('d/m/y') . ' au ' . $end->format('d/m/y');
    }

    public function headings(): array
    {
        return [
            "Matricule",
            "Nom",
            "Prénom",
            "Email",
            "Contact",
            "Rôle",
            "Société",
            "Département",
            "Type de collaborateur",
            "Catégorie professionnelle",
            "Date",
            "Méthode de paiement",
            "Type du plat",
            "Statut du plat",
            "Contribution collaborateur",
            "Subvention ciprel",
        ];
    }

    public function map($row): array
    {
        $order = $row->count() > 1 ? $row->where('type', 'lunch')->first() : $row->first();
        $date = $order->type == 'lunch' ? $order->menu->served_at : $order->created_at;

        // Recuperation de la facturation
        if ($order->user) {
            $userBill = BillingHelper::getUserBill($order->user, $row);
            $contribution =  $userBill['contribution'];
            $subvention = $userBill['subvention'];
        } else {
            $contribution = '(N/A)';
            $subvention = '(N/A)';
        }


        return [
            $order->user?->identifier,
            $order->user?->last_name,
            $order->user?->first_name,
            $order->user?->email,
            $order->user?->contact,
            $order->user?->role->name,
            $order->user?->organization->name,
            $order->user?->department->name,
            $order->user?->userType->name,
            $order->user?->employeeStatus->name,
            $date->format('d/m/Y'),
            $order->user?->accessCard?->paymentMethod->name,
            "Déjeuner",
            $order->state::description(),
            (string) $contribution,
            (string) $subvention,
        ];
    }



    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:P' . $sheet->getHighestRow());

        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:P' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
