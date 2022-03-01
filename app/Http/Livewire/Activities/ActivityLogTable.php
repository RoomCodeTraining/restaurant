<?php

namespace App\Http\Livewire\Activities;

use App\Support\ActivityHelper;
use App\Exports\ActivityLogExport;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ActivityLogTable extends DataTableComponent
{



  public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";
  public bool $showSearch = false;


  public array $filterNames = [
    'activity_at' => 'Activités du',
  ];


  public array $bulkActions = [
    'exportActivityToUser' => 'Export au format Excel',
  ];



  public function mount()
  {
    $this->filters['activity_at'] = now()->format('Y-m-d');
  }

  public function columns(): array
  {
    return [
      Column::make('Date', "created_at")->format(fn ($row) => \Carbon\Carbon::parse($row)->locale('FR_fr')->isoFormat('dddd D MMMM YYYY'))->sortable(),
      Column::make('Heure', "created_at")->format(fn ($row) => $row->format('H:i'))->sortable(),
      Column::make('Mener par', 'causer_id')->format(fn ($row) => ActivityHelper::createdBy($row))->searchable(),
      Column::make('Action', 'event')->searchable(),
    ];
  }


  public function exportActivityToUser()
  {
    return (new ActivityLogExport($this->getFilter('activity_at')))->download('activites-utilisateur.xlsx');
  }


  public function filters(): array
  {
    return [
      'activity_at' => Filter::make('Date')->date(),
    ];
  }

  public function query(): Builder
  {
    return Activity::query()->whereDate('created_at', $this->getFilter('activity_at'))->orderByDesc('created_at');
  }


}
