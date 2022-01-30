<?php

namespace App\Http\Livewire\Suggestions;

use App\Models\User;
use App\Models\SuggestionBox;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class SuggestionBoxTable extends DataTableComponent
{

    public function columns(): array
    {
        return [
            Column::make('Date de création')->format(fn($col, $val, SuggestionBox $row) => $row->created_at->format('d-m-Y') ),
            Column::make('Auteur')->format(fn($col, $val,$row) => $row->user->full_name),
            Column::make('suggestions', 'suggestion'),
        ];
    }

    public function query(): Builder
    {
       return SuggestionBox::query();
    }
}
