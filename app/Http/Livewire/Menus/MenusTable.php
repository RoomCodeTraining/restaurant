<?php

namespace App\Http\Livewire\Menus;

use App\Actions\Menu\DeleteMenuAction;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MenusTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $menuIdBeingDeleted;
    public $confirmingMenuDeletion = false;

    public $menuIdBeingRestored;
    public $confirmingMenuRestoration = false;

    public function columns(): array
    {
        return [
            Column::make('Date', 'served_at')->format(fn ($value) => $value->format('d/m/Y'))->sortable()->searchable(),
            Column::make('Entrées', 'starterDish.name')->sortable()->searchable(),
            Column::make('Desserts', 'dessertDish.name')->sortable()->searchable(),
            Column::make('Plat 1', 'mainDish.name')->sortable()->searchable(),
            Column::make('Plat 2', 'secondDish.name')->sortable()->searchable(),
            Column::make('Actions')->format(function ($value, $column, Menu $row) {
                return view('livewire.menus.table-actions', ['menu' => $row]);
            }),

        ];
    }

    public function confirmMenuDeletion($dishId)
    {
        $this->menuIdBeingDeleted = $dishId;
        $this->confirmingMenuDeletion = true;
    }

    public function deleteMenu(DeleteMenuAction $action)
    {
        $menu = Menu::find($this->menuIdBeingDeleted);
        $action->execute($menu);
        $this->confirmingMenuDeletion = false;
        $this->menuIdBeingDeleted = null;

        session()->flash('success', "Le menu a été supprimé avec succès !");

        return redirect()->route('menus.index');
    }

    public function modalsView(): string
    {
        return 'livewire.menus.modals';
    }

    public function query(): Builder
    {
        return Menu::query();
    }
}
