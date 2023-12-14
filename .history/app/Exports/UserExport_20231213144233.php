<?php

namespace App\Exports;

use App\Models\User;
use App\Support\BillingHelper;
use App\Support\DateTimeHelper;
use Illuminate\Database\Eloquent\Collection;
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
        return User::query()
            ->with('role', 'department', 'employeeStatus', 'userType', 'accessCard')
            ->orderByDesc('created_at')
            ->get();
    }


    public function title(): string
    {
        return "Liste des utilisateurs";
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
            "Statut professionnel",
            "Numéro de Carte NFC",
            "Réchargement petit dejeuner",
            "Réchargement déjeuner",
            "État du compte"
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
            $row->department->name,
            $row->employeeStatus->name,
            $row->currentAccessCard->identifier ?? "Aucune Carte associée",
            $row->accessCard?->breakfast_reload_count,
            $row->accessCard?->lunch_reload_count,
            $row->is_active ? "Actif" : "Inactif"

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
