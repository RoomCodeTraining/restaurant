<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    use Exportable;

    public $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        // dd($this->record);
        $orders = Order::with('user', 'menu')->whereState('state', [Completed::class, Confirmed::class])
            ->get();

        dd($orders);
        // return $orders;
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
}
