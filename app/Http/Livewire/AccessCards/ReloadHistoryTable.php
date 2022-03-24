<?php

namespace App\Http\Livewire\AccessCards;

use App\Models\ReloadAccessCardHistory;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class ReloadHistoryTable extends DataTableComponent
{

    public function columns(): array
    {
        return [
            Column::make('Date')->format(fn($attr, $col, $row) => \Carbon\Carbon::parse($row->created_at)->format('d/m/Y')),
            Column::make('Nom & Prénoms')->format(fn($col, $attr, $row) => $row->accessCard->user->full_name)->searchable(function ($builder, $term) {
                  return $builder->whereHas('accessCard', function ($query) use ($term) {
                      $query->whereHas('user', function ($query) use ($term) {
                          $query->where('first_name', 'like', '%' . $term . '%')
                                ->orWhere('last_name', 'like', '%' . $term . '%');
                      });
                  });
             }),
            Column::make('Type')->format(fn($attr, $col, $row) => $row->quota_type == 'lunch' ? 'Déjeuner' : 'Petit déjeuner'),
            Column::make('Quota', 'quota'),
        ];
    }

    public function query(): Builder
    {
       return ReloadAccessCardHistory::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }
}
