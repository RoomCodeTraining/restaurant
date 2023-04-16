<?php

namespace App\Http\Livewire\Suggestions;

use App\Models\User;
use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use App\Exports\SuggestionExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class SuggestionBoxTable extends DataTableComponent
{

  public $suggestionIdBeingDeleted;
  public $confirmingSuggestionDeletion = false;

  public bool $showSearch = false;

  public function columns(): array
  {
    return [
      Column::make('Date de création')->format(fn ($col, $val, SuggestionBox $row) => $row->created_at->format('d/m/Y'))->sortable(fn($query, $search) => $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE '%{$search}%'")),
      Column::make('Suggerant')->format(fn ($col, $val, $row) => $row->user->full_name)->sortable(function(Builder $builder, $searchTerme){
          $builder->whereHas('user', fn($query) => $query->where('first_name', 'like', "%{$searchTerme}%"))->orWhere('last_name', 'like', "%{$searchTerme}%");
      }),
      Column::make('Objet', 'suggestion_type_id')->format(
        fn ($col, $val, $row) => $row->suggestionType?->name
      )->sortable(),
      Column::make('Suggestion', 'suggestion')->sortable(),
      Column::make('Actions')->format(fn ($val, $col, SuggestionBox $suggestion) => view('livewire.suggestions.table-actions', ['suggestion' => $suggestion])),
    ];
  }



  public function modalsView(): string
  {
      return 'livewire.suggestions.modals';
  }


  public function confirmSuggestionDeletion($suggestionId)
  {
      $this->suggestionIdBeingDeleted = $suggestionId;
      $this->confirmingSuggestionDeletion = true;
  }


  public function deleteSuggestion()
  {
      \App\Models\SuggestionBox::whereId($this->suggestionIdBeingDeleted)->delete();
      $this->confirmingSuggestionDeletion = false;
      session()->flash('success', "La suggestion a été supprimé avec succès !");
      return redirect()->route('suggestions-box.index');
  }

  public function query(): Builder
  {
    if (auth()->user()->hasRole(\App\Models\Role::ADMIN_RH)) {
      return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function($query){
          $query->whereId(SuggestionType::IMPROVEMENT_CANTEEN_SERVICE);
      });
    }

    if(auth()->user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL)){
      return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function($query){
          $query->whereId(SuggestionType::IMPROVEMENT_APPLICATION);
      });
    }

    if(auth()->user()->hasRole(\App\Models\Role::ADMIN)){
       return SuggestionBox::query()
                ->when($this->getFilter('created_at'), fn($query, $created_at) => $query->whereDate('created_at', $created_at))
                ->when($this->getFilter('suggestion_type_id'), fn($query, $suggestion_type_id) => $query->where('suggestion_type_id', $suggestion_type_id))
                ->with('suggestionType')->latest();
    }

    return SuggestionBox::query()->with('suggestionType')->whereUserId(auth()->user()->id)->latest();
  }

  public function filters() : array
  {
    return [
      'created_at' => Filter::make('Date')->date(),
      'suggestion_type_id' => Filter::make('Objet')->select(SuggestionType::all()->pluck('name', 'id')->toArray()),
    ];
  }

  public function bulkActions(): array
  {
    return ['export' => 'Exporter'];
  }

  public function export()
  {
    $dataSelected = $this->selectedRowsQuery->get();
    return Excel::download(new SuggestionExport($dataSelected), 'suggestions.xlsx');
  }
}
