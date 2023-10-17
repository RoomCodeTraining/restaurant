<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpecialMenuExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $menus;


    public function headings(): array
    {
        return [
          'Menu du',
          'Plat principal'
          ];
    }

    public function map($menu): array
    {
        return [
          \Carbon\Carbon::parse($menu->served_at)->format('d/m/Y'),
          $menu->dish->name,
        ];
    }


    public function title() : string
    {
        return 'Liste des menus B';
    }


    public function collection()
    {
        return \App\Models\MenuSpecal::query()->with('dish')->get();
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:B' . $sheet->getHighestRow());

        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('B2:E' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
