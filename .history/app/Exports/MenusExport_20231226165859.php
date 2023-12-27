<?php

namespace App\Exports;

use App\Models\Menu;
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



class MenusExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // dd($this->record);
        return $this->record;
    }

    public function title(): string
    {
        return "Liste des Menu A";
    }

    public function headings(): array
    {

        $heading = [
            'Menu du',
            'Entrées',
            'Plat 1',
            'Plat 2',
            'Déssert'
        ];

        return $heading;
    }

    public function map($row): array
    {
        return [
            $row->served_at,
            $row->starter ? $row->starter->name : 'Aucun',
            $row->main_dish->name,
            $row->secondDish ? $row->secondDish->name : 'Aucun',
            $row->dessert ? $row->dessert->name : 'Aucun'
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
