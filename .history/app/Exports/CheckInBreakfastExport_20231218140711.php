<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Order;
use App\States\Order\Cancelled;
use App\Support\BillingHelper;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\DateTimeHelper;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckInBreakfastExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStyles
{

    use Exportable;
    protected $period;


    public function __construct($period)
    {
        $this->period = $period;
        // dd($this->period);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $orders = Order::query()
            ->withoutGlobalScope('lunch')
            ->whereType('lunch')
            ->with('user', 'menu')
            ->whereState('state', [Completed::class, Confirmed::class, Cancelled::class])
            ->whereBetween('created_at',  DateTimeHelper::inThePeriod($this->period))
            ->get();

        //dd($orders);


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
    {


        $date = $row->created_at;
        if ($row->user) {
            $userBill = BillingHelper::getUserBill($row->user, $row);
            $contribution =  $userBill['contribution'];
            $subvention = $userBill['subvention'];
        } else {
            $contribution = '(N/A)';
            $subvention = '(N/A)';
        }

        $order_type = 'petit déjeuner';
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
