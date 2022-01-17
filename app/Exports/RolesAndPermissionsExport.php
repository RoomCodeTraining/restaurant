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

class RolesAndPermissionsExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{


    private $data;



    public function __construct()
    {
        $this->data = \App\Models\Role::query()->with('permissions')->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return  \App\Models\Role::query()->with('permissions')->get();
    }

    public function title(): string
    {
        return "Liste des rôles et permissions";
    }

    public function headings(): array
    {
        foreach ($this->data as $key => $value) {
             $heading[] = $value->name;
        }

        return $heading;
    }

    public function map($row): array
    {
        foreach ($row->permissions as $key => $value) {
            $table[] = $value->description;
        }
       
        return $table;
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
