<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\Support\BillingHelper;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    use Exportable;
    public User $user;
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $orders = Order::query()
            //->whereType('breakfast')
            ->with('user', 'menu')
            ->whereState('state', [Completed::class, Confirmed::class])
            // ->whereBetween('created_at',  DateTimeHelper::inThePeriod($this->period))
            ->get();


        return $orders;
    }


    public function title(): string
    {
        return 'Pointage pet dej';
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
            'Type du plat',
            "Méthode de paiement",
            "Contribution collaborateur",
            "Subvention ciprel",
        ];
    }

    public function map($row): array
    // {


    //     $date = $row->created_at;
    //     if ($row->user) {
    //         $userBill = BillingHelper::getUserBill($row->user, $row);
    //         $contribution =  $userBill['contribution'];
    //         $subvention = $userBill['subvention'];
    //     } else {
    //         $contribution = '(N/A)';
    //         $subvention = '(N/A)';
    //     }

        $order_type = 'petit déjeuner';
        return [
            $row->user->identifier,
            $row->user->last_name,
            $row->user->first_name,
            $row->user->email,
            $row->user->contact,
            $row->user->role->name,
            $row->user->organization->name,
            $row->user->department->name,
            $row->user->userType->name,
            $row->user->employeeStatus->name,
            $date->format('d/m/Y'),
            'petit déjeuner',
            'Moyen de paiement',
            $contribution,
            $subvention,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:O' . $sheet->getHighestRow());

        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:O' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}