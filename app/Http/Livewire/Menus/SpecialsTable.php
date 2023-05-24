<?php

namespace App\Http\Livewire\Menus;

use App\Models\MenuSpecal;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SpecialsTable extends DataTableComponent
{

    public $menuIdBeingDeleted;
    public $confirmingMenuDeletion = false;

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
            Column::make('Actions', 'actions')
                ->format(function($value, $column, $row) {
                    return view('livewire.menus.special-table-actions', ['menuSpecial' => $row]);
                })
        ];
    }

    public function query(): Builder
    {
        return MenuSpecal::query()->latest();
    }

    public function confirmMenuDeletion($menuId)
    {
        $this->menuIdBeingDeleted = $menuId;
        $this->confirmingMenuDeletion = true;
    }

    public function deleteMenu()
    {
        $menu = MenuSpecal::find($this->menuIdBeingDeleted);
        $menu->delete();
        $this->confirmingMenuDeletion = false;
        session()->flash('success', 'Menu supprimé avec succès !');

        return redirect()->route('menus-specials.index');
    }

    public function modalsView(): string
    {
        return 'livewire.menus.special-modals';
    }
}
