<?php

namespace App\Exports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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
        return $this->record;
    }

    public function title(): string
    {
        return "Liste des commandes du jour";
    }

    public function headings(): array
    {

        $heading = [
            'Nom & Prénoms',
            'Plat commandé',
            'Type de commande'
        ];

        return $heading;
    }

    public function map($row): array
    {


        return [
            $row->user->full_name,
            $row->dish->name,
            $row->is_for_the_evening ? 'Commande du soir' : 'Commande de midi'
        ];
    }
}
