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

    public function map($record): array
    {

        return [
            $record->created_at->format('d/m/Y'),
            $record->identifier,
            $record->last_name,
            $record->first_name,
            $record->email,
            $record->contact,
            $record->role->name,
            $record->organization->name,
            $record->department->name,
            $record->employeeStatus->name,
            $record->currentAccessCard->identifier ?? "Aucune Carte associée",
            $record->accessCard?->breakfast_reload_count,
            $record->accessCard?->lunch_reload_count,
            $record->is_active ? "Actif" : "Inactif"

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