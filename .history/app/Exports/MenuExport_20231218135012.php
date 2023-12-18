<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\Support\BillingHelper;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    use Exportable;
    public User $user;
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $orders = Order::query()
            ->withoutGlobalScope('lunch')
            //->whereType('breakfast')
            ->with('user', 'menu')
            ->whereState('state', [Completed::class, Confirmed::class])
            // ->whereBetween('created_at',  DateTimeHelper::inThePeriod($this->period))
            ->get();


        return $orders;
    }


    public function title(): string
    {
        return 'Pointage pet dej';
    }




    public function headings(): array
    {
        return [
            "Matricule",
            "Nom",
            "Prénom",
            "Email",
            "Contact",
            "Rôle",
            "Société",
            "Département",
            "Type de collaborateur",
            "Catégorie professionnelle",
            "Date",
            'Type du plat',
            "Méthode de paiement",
            "Contribution collaborateur",
            "Subvention ciprel",
        ];
    }

    public function map($row): array
    {

        dd($row);
        $date = $row->created_at;
        if ($row->user) {
            $userBill = BillingHelper::getUserBill($row->user, $row);
            $contribution =  $userBill['contribution'];
            $subvention = $userBill['subvention'];
        } else {
            $contribution = '(N/A)';
            $subvention = '(N/A)';
        }

        $order_type = 'petit déjeuner';
        return [
            $row->user?->identifier,
            $row->user?->last_name,
            $row->user?->first_name,
            $row->user?->email,
            $row->user?->contact,
            $row->user?->role->name,
            $row->user?->organization->name,
            $row->user?->department->name,
            $row->user?->userType->name,
            $row->user?->employeeStatus->name,
            $date->format('d/m/Y'),
            'petit déjeuner',
            'Moyen de paiement',
            $contribution,
            $subvention,
        ];
    }
}
