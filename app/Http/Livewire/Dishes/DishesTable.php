<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\DeleteDishAction;
use App\Models\Dish;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DishesTable extends DataTableComponent
{
    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $dishIdBeingDeleted;
    public $confirmingDishDeletion = false;

    public $dishIdBeingRestored;
    public $confirmingDishRestoration = false;

    public function columns(): array
    {
        return [
            Column::make('Date création', 'created_at')->format(fn ($value) => $value->format('d/m/Y'))->sortable()->searchable(),
            Column::make('Libellé', 'name')->sortable()->searchable(),
            Column::make('Type plat', 'dishType.name')->sortable()->searchable(),
            Column::make('Actions')->format(function ($value, $column, Dish $row) {
                return view('livewire.dishes.table-actions', ['dish' => $row]);
            }),

        ];
    }

    public function confirmDishDeletion($dishId)
    {
        $this->dishIdBeingDeleted = $dishId;
        $this->confirmingDishDeletion = true;
    }

    public function deleteDish(DeleteDishAction $action)
    {
        $dish = Dish::find($this->dishIdBeingDeleted);
        $action->execute($dish);
        $this->confirmingDishDeletion = false;
        $this->dishIdBeingDeleted = null;

        session()->flash('success', "Le plat a été supprimé avec succès !");

        return redirect()->route('dishes.index');
    }

    public function modalsView(): string
    {
        return 'livewire.dishes.modals';
    }

    public function query(): Builder
    {
        return Dish::query();
    }
}
