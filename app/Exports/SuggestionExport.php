<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuggestionExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles
{
    use Exportable;
    public function __construct(public $suggestions){}


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->suggestions;
    }

    public function title(): string
    {
        return 'Liste des suggestions';
    }

    public function headings(): array
    {
        return [
            'Suggestion',
            'Type de suggestion',
            'Utilisateur',
            'Date de création',
        ];
    }

    public function map($suggestion): array
    {
        return [
            $suggestion->suggestion,
            $suggestion->suggestionType->name,
            $suggestion->user->full_name,
            $suggestion->created_at->format('d/m/Y'),
        ];
    }

       public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:D' . $sheet->getHighestRow());

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:D' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }


}
