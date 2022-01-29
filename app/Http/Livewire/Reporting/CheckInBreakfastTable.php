<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\DateTimeHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class CheckInBreakfastTable extends DataTableComponent
{
    public bool $showSearch = false;


    public array $filterNames = [
        'start_date' => 'A partir du',
        'end_date' => "Jusqu'au",
        'state' => "Statut",
        'in_the_period' => 'Dans la période',
        'order_by_other' => 'Type de commande'
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
            Column::make("Type d'utilisateur", 'user_type_name')->format(fn($val, $col, $row) => $row->user->userType->name),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.check-state', ['order' => $row])),
            //Column::make('Nbr. de commandes', 'total_orders')->,
            //Column::make('Actions')->format(fn ($val, $col, $row) => view('livewire.reporting.table-actions', ['row' => $row]))
        ];
    }

    public function query(): Builder
    {
        $query =  Order::query()->with('user', 'menu')
        ->where('type', 'breakfast')
        ->unless($this->filters['state'], fn ($q) => $q->whereState('state', [Confirmed::class, Completed::class]))
        ->when($this->filters['state'], fn ($q) => $q->whereState('state', $this->filters['state']))
        ->whereBetween('created_at', DateTimeHelper::inThePeriod($this->getFilter('in_the_period')));
    
    //$this->orders = $q->get();    
         return $query;
    }

    public function filters(): array
    {
        return [

            'state' => Filter::make('Statut')->select([
                '' => 'Tous',
                Confirmed::$name => 'Non Consommées',
                Completed::$name => 'Consommées',
            ]),
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
