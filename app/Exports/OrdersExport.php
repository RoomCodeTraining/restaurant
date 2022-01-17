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

    public function __construct(?string $period, ?string $state)
    {
        $this->period = $period;
        $this->state = $state;
    }

    public function collection()
    {
        return Order::query()
            ->withoutGlobalScopes()
            ->whereNotNull('payment_method_id')
            ->with('menu', 'user.role', 'user.department', 'user.employeeStatus', 'user.userType')
            ->unless($this->state, fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->state, fn ($query) => $query->whereState('state', $this->state))
            ->whereBetween('created_at', DateTimeHelper::inThePeriod($this->period))
            ->get()->groupBy('user_id')
            ->map(fn ($row) => $row->groupBy(fn ($item) => $item->type == 'lunch' ? $item->menu->served_at->format('Y-m-d') : $item->created_at->format('Y-m-d')))
            ->flatten(1);
    }

    public function title(): string
    {
        [$start, $end] = DateTimeHelper::inThePeriod($this->period);

        return 'Reporting des commandes du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');
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
            "Méthode de paiement",
            "Statut du plat",
            "Type du plat",
            "A payer",
            "Subvention",
        ];
    }

    public function map($row): array
    {
        $order = $row->count() > 1 ? $row->where('type', 'lunch')->first() : $row->first();
        $date = $order->type == 'lunch' ? $order->menu->served_at : $order->created_at;
        $userBill = BillingHelper::getUserBill($order->user, $row);
        $mealLabel = $row->count() > 1 ? 'petit déjeuner + déjeuner' : ($order->type == 'lunch' ? 'déjeuner' : 'petit déjeuner');

        return [
            $order->user->last_name,
            $order->user->first_name,
            $order->user->email,
            $order->user->contact,
            $order->user->role->name,
            $order->user->organization->name,
            $order->user->department->name,
            $order->user->userType->name,
            $order->user->employeeStatus->name,
            $date->format('d/m/Y'),
            $order->paymentMethod->name,
            $order->state::description(),
            $mealLabel,
            $userBill['contribution']['lunch'] + $userBill['contribution']['breakfast'],
            $userBill['subvention']['lunch'] + $userBill['subvention']['breakfast'],
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
