<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Order;
use App\States\Order\Completed;
use App\States\Order\Confirmed;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class FilterTable extends DataTableComponent
{
    public bool $showSearch = false;


    public array $filterNames = [
        'start_date' => 'A partir du',
        'end_date' => "Jusqu'au",
        'state' => "Statut",
        'in_the_period' => 'Dans la période',
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


    

    public function query()
    {
        $q =  Order::query()
                ->join('users', 'orders.user_id', 'users.id')
                ->join('user_types', 'users.user_type_id', 'user_types.id')
                ->join('menus', 'orders.menu_id', 'menus.id')
                ->unless($this->filters['state'], fn($q) => $q->whereState('state', [Confirmed::class, Completed::class]))
                ->when($this->filters['state'], fn($q) => $q->where('state', $this->filters['state']))
                ->whereBetween('menus.served_at', $this->getPeriod($this->getFilter('in_the_period')))
                ->orderBy('users.last_name', 'desc')
                ->groupBy('user_id')
                ->selectRaw('
                    users.id AS user_id,
                    users.identifier AS user_identifier,
                    CONCAT(users.last_name, " ", users.first_name) AS user_full_name,
                    user_types.name AS user_type_name,
                    COUNT(orders.id) AS total_orders
                ');
       return $q;

    }

    public array $bulkActions = [
        'exportToExcel' => 'Export au format Excel',
    ];


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

    public static function getPeriod($value): array
    {
        $begin = now();
        $end = now();

        switch ($value) {
            case 'today':
                $begin->startOfDay();
                $end->endOfDay();

                break;
            case 'yesterday':
                $begin->subDay()->startOfDay();
                $end->subDay()->endOfDay();

                break;
            case 'tomorrow':
                $begin->addDay()->startOfDay();
                $end->addDay()->endOfDay();

                break;
            case 'last_month':
                $begin->subMonth()->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            case 'this_month':
                $begin->startOfMonth();
                $end->endOfMonth();

                break;
            case 'next_month':
                $begin->addMonth()->startOfMonth();
                $end->addMonth()->endOfMonth();

                break;
            case 'last_year':
                $begin->subYear()->startOfYear();
                $end->subYear()->endOfYear();

                break;
            case 'this_year':
                $begin->startOfYear();
                $end->endOfYear();

                break;
            case 'next_year':
                $begin->addYear()->startOfYear();
                $end->addYear()->endOfYear();

                break;
            case 'this_week':
                $begin->startOfWeek();
                $end->endOfWeek();

                break;
            case 'last_week':
                $begin->subWeek()->startOfWeek();
                $end->subWeek()->endOfWeek();

                break;
            case 'this_quarter':
                $begin->startOfMonth();
                $end->addMonths(2)->endOfMonth();

                break;
            case 'last_quarter':
                $begin->subMonths(3)->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            default:
                break;
        }

        return [$begin, $end];
    }


    
}
