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
        // $this->period = $period;
        // $this->state = $state;
    }

    public function collection()
    {

        return $this->record;
        //dd($this->record);
        // $p = Order::query()
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

        // dd($p);
        //return
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


        // $order = $row->count() > 1 ? $row->where('type', 'lunch')->first() : $row->first();
        // $date = $order->type == 'lunch' ? $order->menu->served_at : $order->created_at;

        $order = $row->count() > 1 ? $row->where('type', 'lunch')->first() : $row->first();
        $date = $row->type == 'lunch' ? $row->menu->served_at : $row->created_at;


        // Recuperation de la facturation
        if ($row->user) {
            $userBill = BillingHelper::getUserBill($row->user, $row);
            $contribution =  $userBill['contribution'];
            $subvention = $userBill['subvention'];
        } else {
            $contribution = '(N/A)';
            $subvention = '(N/A)';
        }

        // dd($row->user->paymentMethod?->name);
        return [
            $row->user?->identifier,
            $row->user?->last_name,
            $row->user?->first_name,
            $row->user?->email,
            $row->user?->contact,
            $row->user?->role->name,
            $row->user?->organization->name,
            $row->user?->department->name,
            $row->user?->userType->name,
            $row->user?->employeeStatus->name,
            $date->format('d/m/Y'),
            $row->user?->accessCard?->paymentMethod->name,
            "Déjeuner",
            $row->state == 'confirmed' ? 'Commande non consommée', 'Commande consommée',
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