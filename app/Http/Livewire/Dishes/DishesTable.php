<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\DeleteDishAction;
use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

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
            Column::make('Type plat', 'dishType.name'),
            Column::make('Actions')->format(fn ($value, $column, Dish $row) => view('livewire.dishes.table-actions', ['dish' => $row])),

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

    public function filters(): array
    {
        return [
            'type' => Filter::make('Type de plat')->multiSelect([
                DishType::STARTER => 'Entrée',
                DishType::MAIN => 'Plat principal',
                DishType::DESSERT => 'Déssert',
            ])
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.dishes.modals';
    }

    public function query(): Builder
    {
        return Dish::query()
            ->with('dishType')
            ->when($this->getFilter('type'), fn ($query, $type) => $query->WhereIn('dish_type_id', $type));
    }
}
