<?php

namespace App\Http\Livewire\Suggestions;

use App\Models\User;
use App\Models\SuggestionBox;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
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
    if (!auth()->user()->hasROle(\App\Models\Role::ADMIN)) {
      return SuggestionBox::query()->where('user_id', auth()->user()->id);
    }

    return SuggestionBox::query();
  }
}
