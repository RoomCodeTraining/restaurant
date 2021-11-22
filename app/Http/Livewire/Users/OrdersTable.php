<?php

namespace App\Http\Livewire\Users;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class OrdersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public function columns(): array
    {
        return [
            Column::make('Date de création', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Matricule', 'user.identifier')->sortable()->searchable(),
            Column::make('Nom & Prénoms', 'user.full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('user.first_name', 'like', '%' . $term . '%')
                    ->orWhere('user.last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Plat', 'dish.name')->sortable()->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            'date' => Filter::make('Date')->date()
        ];
    }

    public function query(): Builder
    {
        return Order::with('user', 'menu', 'dish');
    }
}
