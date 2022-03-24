<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class QuotaExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection 
    */
    public $users;
    

    public function headings(): array
    {
      return [
        'Date de verification',
        'Matricule/identenfiant',
        'Nom & Prénoms',
        'Quota petit dejeuner',
        'Quota dejeuner',
      ];
    }

    public function map($user): array
    {
      return [
        now()->format('d/m/Y'),
        $user->identifier,
        $user->full_name,
        $user->accessCard->quota_breakfast,
        $user->accessCard->quota_lunch,
      ];
    }


    public function title() : string{
      return 'Verification quota';
    }


    public function collection()
    {
        $users =  \App\Models\User::query()->with('accessCard')->get();
       return $users->filter(function($user){
           if($user->current_access_card_id){
             return $user;
           }
        });
      
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
