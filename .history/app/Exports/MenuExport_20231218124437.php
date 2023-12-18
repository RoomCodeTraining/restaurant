<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class MenuExport implements FromCollection
{
    use Exportable;
    public User $user;
    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(public $collection)
    {
    }

    public function collection()
    {
        return $this->collection;
    }

    public function title(): string
    {
        return 'Rapport hebdomadaire';
    }

    public function headings(): array
    {
        return [
            'Employés',
            'Départements',
            'Sociétés',
            'Nombre de Presence',
            'Jour de la semaine',
            'Date',
            // 'Nombre de mouvement',
        ];
    }

    public function map($row): array
    {

        return [
            $row['full_name'],
            $row['department'],
            $row['organization'],
            $row['present_count'],
            $row['jour_presence'],
            $row['date_presence'],
            // $row['nbre_mouvment']

        ];
}