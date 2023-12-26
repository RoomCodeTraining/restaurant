<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DishExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public $menus;


    public function headings(): array
    {
        return [
            'Menu du',
            'Plat',
        ];
    }

    public function map($menu): array
    {
        return [
            $menu->served_at->format('d/m/Y'),
            $menu->starter->name,
            $menu->main_dish->name,
            $menu->second_dish ? $menu->second_dish->name : 'Pas de second plat',
            $menu->dessert->name,
        ];
    }


    public function title(): string
    {
        return 'Liste des menus de la cantine';
    }


    public function collection()
    {
        return \App\Models\Menu::query()->with('dishes')->get();
    }


    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:E' . $sheet->getHighestRow());

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:E' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
