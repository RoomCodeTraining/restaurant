<?php

namespace App\Http\Livewire\Suggestions;

use App\Exports\SuggestionExport;
use App\Models\SuggestionBox;
use App\Models\SuggestionType;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class SuggestionBoxTable extends DataTableComponent
{

    public $suggestionIdBeingDeleted;
    public $confirmingSuggestionDeletion = false;

    public bool $showSearch = false;

    public function columns(): array
    {
        return [
          Column::make('Date de création')->format(fn ($col, $val, SuggestionBox $row) => $row->created_at->format('d/m/Y'))->sortable(fn ($query, $search) => $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE '%{$search}%'")),
          Column::make('Suggerant')->format(fn ($col, $val, $row) => $row->user->full_name)->sortable(function (Builder $builder, $searchTerme) {
              $builder->whereHas('user', fn ($query) => $query->where('first_name', 'like', "%{$searchTerme}%"))->orWhere('last_name', 'like', "%{$searchTerme}%");
          }),
          Column::make('Objet', 'suggestion_type_id')->format(
              fn ($col, $val, $row) => $row->suggestionType?->name
          )->sortable(),
          Column::make('Suggestion', 'suggestion')->sortable(),
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
            return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function ($query) {
                $query->whereId(SuggestionType::IMPROVEMENT_CANTEEN_SERVICE)->orWhere('user_id', auth()->user()->id);
            });
        }

        if(auth()->user()->hasRole(\App\Models\Role::ADMIN_TECHNICAL)) {
            return SuggestionBox::query()->with('suggestionType')->whereHas('suggestionType', function ($query) {
                $query->whereId(SuggestionType::IMPROVEMENT_APPLICATION)->orWhere('user_id', auth()->user()->id);
            });
        }

        if(auth()->user()->hasRole(\App\Models\Role::ADMIN)) {
            return SuggestionBox::query()
                     ->when($this->getFilter('created_at'), fn ($query, $created_at) => $query->whereDate('created_at', $created_at))
                     ->when($this->getFilter('suggestion_type_id'), fn ($query, $suggestion_type_id) => $query->where('suggestion_type_id', $suggestion_type_id))
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

        if($dataSelected->isEmpty()) {
            session()->flash('error', "Aucune suggestion n'a été sélectionné !");

            return redirect()->route('suggestions-box.index');
        }

        return Excel::download(new SuggestionExport($dataSelected), 'suggestions.xlsx');
    }
}
