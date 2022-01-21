<?php

namespace App\Exports;

use App\Models\Role;
use App\Support\BillingHelper;
use App\Support\DateTimeHelper;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermissionSheets implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{


    public $role;
    public function __construct($role)
    {
        $this->role = $role;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function Collection()
    {
        return $this->role->permissions;
    }

    public function title(): string
    {
        return "{$this->role->name}";
    }

    public function headings(): array
    {
        return [
            'permission',
        ];
    }

    public function map($row): array
    {
        return [
            $row->description,
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:A' . $sheet->getHighestRow());

        $sheet->getStyle('A1:A1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 13],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(20);

        $sheet->getStyle('A2:A' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
