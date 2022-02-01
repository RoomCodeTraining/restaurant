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

  public function columns(): array
  {
    return [
      Column::make('Date de création')->format(fn ($col, $val, SuggestionBox $row) => $row->created_at->format('d-m-Y')),
      Column::make('Auteur')->format(fn ($col, $val, $row) => $row->user->full_name),
      Column::make('Suggestion', 'suggestion'),
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
    if (auth()->user()->can('suggestion.manage', \App\Models\SuggestionBox::class)) {
      return SuggestionBox::query()->where('user_id', auth()->user()->id);
    }

    return SuggestionBox::query();
  }
}
