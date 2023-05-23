<?php

namespace App\Http\Livewire\Menus;

use App\Models\MenuSpecal;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SpecialsTable extends DataTableComponent
{

    public function columns(): array
    {
        return [
            Column::make('Date', 'created_at')
                ->format(function($value, $column, $row) {
                    return $row->created_at->format('d/m/Y');
                })
                ->sortable()
                ->searchable(),
            Column::make('Plat', 'dish_id')
                ->format(function($value, $column, $row) {
                    return $row->dish->name;
                })
                ->sortable()
                ->searchable(),
        ];
    }

    public function query(): Builder
    {
        return MenuSpecal::query()->latest();
    }
}
