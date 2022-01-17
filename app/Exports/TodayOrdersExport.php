<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Support\BillingHelper;
use App\Support\DateTimeHelper;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TodayOrdersExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $data;

    public function __construct()
    {
        $this->data =  \App\Models\Order::OrderBy('dish_id', 'desc')->today()->with('user', 'menu', 'dish')->get();
    }

    public function collection()
    {   
        return $this->data;
    }


    public function title(): string
    {
        return "Liste des commandes du jour non traitées";
    }

    public function headings(): array
    {
        
        $heading = [
            'Matricule',
            'Nom & Prénoms',
            'Plat commandé',
        ];

        return $heading;
    }

    public function map($row): array
    {
  
        return [
            $row->user->identifier,
            $row->user->full_name,
            $row->dish->name,
        ];
    }




    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:C' . $sheet->getHighestRow());

        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:C' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
