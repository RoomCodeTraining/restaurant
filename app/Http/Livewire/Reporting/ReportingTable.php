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
    public $refresh = 1000 * 60;

    public $orders = [];

    public array $filterNames = [
        'start_date' => 'A partir du',
        'end_date' => "Jusqu'au",
        'state' => "Statut",
        'in_the_period' => 'Dans la période',
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
            Column::make('Matricule/Identifiant', 'user_identifier'),
            Column::make('Nom', 'user_full_name'),
            Column::make("Type d'utilisateur", 'user_type_name'),
            Column::make('Nbr. de commandes', 'total_orders'),
            Column::make('Actions')->format(fn ($val, $col, $row) => view('livewire.reporting.table-actions', ['row' => $row]))
        ];
    }

    public function query(): Builder
    {
        $orders =  Order::query()
            ->unless($this->getFilter('state'), fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->getFilter('state'), fn ($query) => $query->whereState('state', $this->getFilter('state')))
            ->whereBetween('orders.created_at', DateTimeHelper::inThePeriod($this->getFilter('in_the_period')))
            //->whereNotNull('orders.payment_method_id')
            ->join('users', 'orders.user_id', 'users.id')
            ->join('user_types', 'users.user_type_id', 'user_types.id')
            //->join('employee_statuses', 'users.employee_status_id', 'employee_statuses.id')
            ->orderBy('users.last_name', 'desc')
            ->groupBy('user_id')
            ->selectRaw('
                users.id AS user_id,
                users.identifier AS user_identifier,
                CONCAT(users.last_name, " ", users.first_name) AS user_full_name,
                user_types.name AS user_type_name,
                COUNT(orders.id) AS total_orders
            ');
      
     
      return $orders;
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
        return (new OrdersExport($this->getFilter('in_the_period'), $this->getFilter('state')))->download('reporting_commandes.xlsx');
    }

    public function showDetails($row)
    {
        $this->orders = Order::query()
            ->with('menu', 'dish')
            ->where('user_id', $row['user_id'])
            ->whereNotNull('payment_method_id')
            ->unless($this->getFilter('state'), fn ($query) => $query->whereState('state', [Confirmed::class, Completed::class]))
            ->when($this->getFilter('state'), fn ($query) => $query->whereState('state', $this->getFilter('state')))
            ->whereBetween('created_at', DateTimeHelper::inThePeriod($this->getFilter('in_the_period')))
            ->get();

        $this->showingDetails = true;
    }
}
