<?php

namespace App\Http\Livewire\AccessCards;

use App\Exports\ReloadAccessCardHistoryExport;
use App\Models\ReloadAccessCardHistory;
use App\Support\DateTimeHelper;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class ReloadHistoryTable extends DataTableComponent
{

    public array $filterNames = [
      'quota_type' => "Type de quota",
      'in_the_period' => 'Dans la période',
    ];


    public $bulkActions = [
      'exportHistory' => 'Exporter en excel',
    ];

    public function mount()
    {
        $this->filters['in_the_period'] = $this->getFilter('in_the_period') ?? 'this_month';
    }

    public function columns(): array
    {
        return [
          Column::make('Date')->format(fn ($attr, $col, $row) => \Carbon\Carbon::parse($row->created_at)->format('d/m/Y')),
          Column::make('Nom & Prénoms')->format(fn ($col, $attr, $row) => $row->accessCard->user->full_name)->searchable(function ($builder, $term) {
              return $builder->whereHas('accessCard', function ($query) use ($term) {
                  $query->whereHas('user', function ($query) use ($term) {
                      $query->where('first_name', 'like', '%' . $term . '%')
                        ->orWhere('last_name', 'like', '%' . $term . '%');
                  });
              });
          }),
          Column::make('Categorie')->format(fn ($col, $attr, $row) => $row->accessCard->user->userType->name),
          Column::make('Société')->format(fn ($col, $attr, $row) => $row->accessCard->user->organization->name),
          Column::make('Methode de paiement')->format(fn ($col, $attr, $row) => $row->accessCard->paymentMethod->name),
          Column::make('Type')->format(fn ($attr, $col, $row) => $row->quota_type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
          Column::make('Quota', 'quota'),
        ];
    }

    public function filters(): array
    {
        return [

          'quota_type' => Filter::make('Type')->select([
            '' => 'Tous',
            'lunch' => 'Déjeuner',
            'breakfast' => 'Petit déjeuner',
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
          'payment_method' => Filter::make('Methode de paiement')->select([
            '' => 'Tous',
            1 => 'Cash',
            2 => 'Postpaid',
            3 => 'Subvention',
          ]),
        ];
    }


    public function query(): Builder
    {
        return ReloadAccessCardHistory::query()
          ->orderByDesc('created_at')
          ->whereHas('accessCard', function ($query) {
              $query->whereHas('user', function ($query) {
                  $query->where('deleted_at', null);
              });
          })
          ->unless($this->filters['quota_type'], fn ($query) => $query->whereIn('quota_type', ['lunch', 'breakfast']))
          ->when($this->filters['quota_type'], fn ($query) => $query->where('quota_type', $this->filters['quota_type']))
          ->when($this->filters['in_the_period'], fn ($query) => $query->whereBetween('created_at', DateTimeHelper::inThePeriod($this->filters['in_the_period'])))->orderBy('created_at', 'desc')
          ->when($this->filters['payment_method'], fn ($query) => $query->whereHas('accessCard', function ($query) {
              $query->where('payment_method_id', $this->filters['payment_method']);
          }));
    }


    public function exportHistory()
    {
        return (new ReloadAccessCardHistoryExport($this->getFilter('in_the_period'), $this->getFilter('quota_type')))->download('rechargements.xlsx');
    }
}
