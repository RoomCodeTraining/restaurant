<?php

namespace App\Exports;

use App\Support\BillingHelper;
use App\States\Order\Cancelled;
use App\States\Order\Suspended;
use App\Support\DateTimeHelper;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TodayOrdersExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        // $this->data =  \App\Models\Order::whereNotState('state', [Suspended::class, Cancelled::class])->OrderBy('dish_id', 'desc')->today()->with('user', 'menu', 'dish')->get();
    }

    public function collection()
    {
        //return $this->data;

        $this->data;
    }


    public function title(): string
    {
        return "Liste des commandes du jour";
    }

    public function headings(): array
    {

        $heading = [
            'Nom & Prénoms',
            'Plat commandé',
        ];

        return $heading;
    }

    public function map($row): array
    {

        return [
            $row->user->full_name,
            $row->dish->name,
        ];
    }




    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:B' . $sheet->getHighestRow());

        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:B' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
