<?php

namespace App\Http\Livewire\Menus;

use App\Models\Menu;
use Maatwebsite\Excel\Facades\Excel;
use App\Actions\Menu\DeleteMenuAction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class MenusTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'served_at';
    public string $defaultSortDirection = 'desc';

    public $menuIdBeingDeleted;
    public $confirmingMenuDeletion = false;

    public $menuIdBeingRestored;
    public $confirmingMenuRestoration = false;


    protected $bulkActions = [
        'exportMenu' =>'Exporter les menus'
    ];

    public function columns(): array
    {
        return [
            Column::make('Menu du', 'served_at')->format(fn ($value) => $value->format('d/m/Y'))->sortable()->searchable(),
            Column::make('Entrées')->format(
                fn ($value, $column, Menu $menu) => $menu->starter->name
            )->searchable(fn ($query, $searchTerm) => $query->orWhereRelation('dishes', 'name', 'like', "%{$searchTerm}%")),
            Column::make('Plat 1')->format(
                fn ($value, $column, Menu $menu) => $menu->main_dish->name
            )->searchable(fn ($query, $searchTerm) => $query->orWhereRelation('dishes', 'name', 'like', "%{$searchTerm}%")),
            Column::make('Plat 2')->format(
                fn ($value, $column, Menu $menu) => $menu->second_dish?->name
            )->searchable(fn ($query, $searchTerm) => $query->orWhereRelation('dishes', 'name', 'like', "%{$searchTerm}%")),
            Column::make('Déssert')->format(
                fn ($value, $column, Menu $menu) => $menu->dessert->name
            )->searchable(fn ($query, $searchTerm) => $query->orWhereRelation('dishes', 'name', 'like', "%{$searchTerm}%")),
            Column::make('Action')->format(fn ($value, $column, Menu $menu) => view('livewire.menus.table-actions')->with([
                'menu' => $menu,
            ])),
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


    public function exportMenu(){
        return Excel::download(new \App\Exports\DishExport, 'liste-des-menus.xlsx');
    }

    public function modalsView(): string
    {
        return 'livewire.menus.modals';
    }

    public function query(): Builder
    {
        return Menu::query()->with('dishes')->withCount('orders');
    }
}
