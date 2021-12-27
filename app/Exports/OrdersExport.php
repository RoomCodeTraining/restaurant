<?php

namespace App\Exports;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\BillingHelper;
use App\Support\DateTimeHelper;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $period;
    protected $state;

    public function __construct(?string $period, ?string $state)
    {
        $this->period = $period;
        $this->state = $state;
    }

    public function query()
    {
        return Order::query()
            ->with('menu', 'user.role', 'user.department', 'user.employeeStatus', 'user.userType')
            ->unless($this->state, fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->state, fn ($query) => $query->whereState('state', $this->state))
            ->whereBetween('created_at', DateTimeHelper::inThePeriod($this->period));
    }

    public function title(): string
    {
        return 'Reporting';
    }

    public function headings(): array
    {
        return [
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
            "A payer",
            "Subvention",
        ];
    }

    public function map($row): array
    {
        $userBill = BillingHelper::getUserBill($row->user, $row->menu->served_at);

        return [
            $row->user->last_name,
            $row->user->first_name,
            $row->user->email,
            $row->user->contact,
            $row->user->role->name,
            $row->user->organization->name,
            $row->user->department->name,
            $row->user->userType->name,
            $row->user->employeeStatus->name,
            $row->menu->served_at->format('d/m/Y'),
            $userBill['lunch']['contribution'] + $userBill['breakfast']['contribution'],
            $userBill['lunch']['subvention'] + $userBill['breakfast']['subvention'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:L' . $sheet->getHighestRow());

        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:L' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
