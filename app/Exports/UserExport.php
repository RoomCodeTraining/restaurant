<?php

namespace App\Exports;

use App\Models\User;
use App\Support\BillingHelper;
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

class UserExport implements FromCollection, WithTitle, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::query()
                    ->with('role', 'department', 'employeeStatus', 'userType')
                    ->orderByDesc('created_at')
                    ->get();

    }


    public function title(): string
    {
        return "Reporting des utilisateurs de l'application config('app.name')";
    }

    public function headings(): array
    {
        return [
            "Date de création",
            "Matricule",
            "Nom",
            "Prénom",
            "Email",
            "Contact",
            "Rôle",
            "Société",
            "Département",
        ];
    }

    public function map($row): array
    {
    
        return [
            $row->created_at->format('d/m/Y'),
            $row->identifier,
            $row->last_name,
            $row->first_name,
            $row->email,
            $row->contact,
            $row->role->name,
            $row->organization->name,
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
