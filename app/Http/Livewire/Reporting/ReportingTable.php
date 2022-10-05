<?php

namespace App\Http\Livewire\Reporting;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use App\Support\DateTimeHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ReportingTable extends DataTableComponent
{
    //public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public bool $showSearch = false;

    public $showingDetails = false;

    public $orders = [];

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
        $this->filters['in_the_period'] = $this->getFilter('in_the_period') ?? 'this_month';
    }

    public function columns(): array
    {

        return [
            Column::make('Menu du')->format(fn ($val, $col, $row) => $row->menu->served_at->format('d/m/Y')),
            Column::make('Nom', 'user_full_name')->format(fn($val, $col, $row) => optional($row->user)->full_name),
            Column::make('Plat')->format(fn($val, $col, Order $row) => $row->dish->name),
            Column::make('Statut')->format(fn ($val, $col, Order $row) => view('livewire.orders.check-state', ['order' => $row])),

            //Column::make('Nbr. de commandes', 'total_orders')->,
            //Column::make('Actions')->format(fn ($val, $col, $row) => view('livewire.reporting.table-actions', ['row' => $row]))
        ];
    }

    public function query()
    {
       
        $query =  Order::query()->with('user', 'menu')
            ->where('type', 'lunch')
            ->unless($this->filters['state'], fn ($q) => $q->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->filters['state'], fn ($q) => $q->whereState('state', $this->filters['state']))
            ->filter($this->getFilter('in_the_period'));
        
        //$this->orders = $q->get();    
        return $query;
    }

    public function modalsView(): string
    {
        return 'livewire.reporting.modals';
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

    public function exportToExcel()
    {
        return (new OrdersExport($this->getFilter('in_the_period'), $this->getFilter('state')))->download('reporting-commandes.xlsx');
    }

    public function showDetails($row)
    {
        $this->orders = Order::query()
            ->join('menus', 'orders.menu_id', 'menus.id')
            ->with('dish')
            ->where('user_id', $row['user_id'])
            ->unless($this->getFilter('state'), fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->getFilter('state'), fn ($query) => $query->whereState('state', $this->getFilter('state')))
            ->whereBetween('menus.served_at', DateTimeHelper::inThePeriod($this->getFilter('in_the_period')))
            ->get();

        $this->showingDetails = true;
    }
}
