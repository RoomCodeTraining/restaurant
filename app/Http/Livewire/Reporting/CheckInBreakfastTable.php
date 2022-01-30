<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Order;
use App\Exports\OrdersExport;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\DateTimeHelper;
use App\Exports\CheckInBreakfastExport;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class CheckInBreakfastTable extends DataTableComponent
{
    public bool $showSearch = false;

    public $orders = [];


    public array $filterNames = [
        'start_date' => 'A partir du',
        'end_date' => "Jusqu'au",
        'in_the_period' => 'Dans la période',
    ];

    public array $bulkActions = [
        'exportToExcel' => 'Export au format Excel',
    ];


    public function mount()
    {
        $this->filters['in_the_period'] = $this->getFilter('in_the_period') ?? 'today';
    }

    public function columns(): array
    {
        return [
            Column::make('Pointage du')->format(fn ($val, $col, $row) => $row->created_at->format('d/m/Y')),
            Column::make('Matricule/Identifiant')->format(fn($val, $col, $row) => $row->user->identifier),
            Column::make('Nom', 'user_full_name')->format(fn($val, $col, $row) => $row->user->full_name),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.check-state', ['order' => $row])),
            //Column::make('Nbr. de commandes', 'total_orders')->,
            //Column::make('Actions')->format(fn ($val, $col, $row) => view('livewire.reporting.table-actions', ['row' => $row]))
        ];
    }

    public function query(): Builder
    {
        $query =  Order::withoutGlobalScope('lunch')->with('user', 'menu')
        ->whereState('state', Completed::class)
        ->whereBetween('created_at', DateTimeHelper::inThePeriod($this->getFilter('in_the_period')));
    
         return $query;
    }


    public function exportToExcel()
    {
        $file_name = today()->format('d-m-Y') . '_pointage_de_petit_dejeuner.xlsx';
        $period = $this->getFilter('in_the_period');
 
        return (new CheckInBreakfastExport($period))->download($file_name);
    }

    public function filters(): array
    {
        return [
            'in_the_period' => Filter::make('Dans la période')->select([
                'today' => "Aujourd'hui",
                'yesterday' => 'Hier',
                'this_week' => "Cette semaine",
                'last_week' => 'La semaine dernière',
                'this_month' => 'Ce mois',
                'last_month' => 'Le mois dernier',
                'this_quarter' => 'Ce trimestre',
                'last_quarter' => 'Le trimestre dernier',
                'this_year' => "Cette année",
                'last_year' => "L'année dernière",
            ]),


        ];
    }
}
