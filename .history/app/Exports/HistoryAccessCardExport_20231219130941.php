<?php

namespace App\Exports;

use App\Models\ReloadAccessCardHistory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HistoryAccessCardExport implements FromCollection
{
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
        $this->record = ReloadAccessCardHistory::query()
            ->whereHas('accessCard', function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('deleted_at', null);
                });
            })

            ->orderBy('created_at', 'desc')->get();;
    }


    public function title(): string
    {
        return "Liste des utilisateurs";
    }

    public function headings(): array
    {
        return [
            "Matricule",
            "Nom",
            "Prénom",
            "Catégorie professionnel",
            "Fonction",
            "Société",
            "Type de collaborateur",
            "Mode de paiement",

        ];
    }

    public function map($row): array
    {

        return [
            // $row->created_at->format('d/m/Y'),
            $row->accessCard->user->identifier,
            $row->accessCard->user->last_name,
            $row->accessCard->user->first_name,
            $row->accessCard->user->employeeStatus->name,
            $row->accessCard->user->department->name,
            $row->accessCard->user->organization->name,
            $row->accessCard->user->role->name,
            $row->accessCard->paymentMethod->name,





        ];
    }



    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:N' . $sheet->getHighestRow());

        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '538ED5']]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(15);

        $sheet->getStyle('A2:N' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }
}
