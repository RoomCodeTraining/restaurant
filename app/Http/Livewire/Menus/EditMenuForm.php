<?php

namespace App\Http\Livewire\Menus;

use App\Models\Dish;
use App\Models\Menu;
use Livewire\Component;
use App\Models\DishType;
use Illuminate\Validation\Rule;
use App\Actions\Menu\UpdateMenuAction;

class EditMenuForm extends Component
{
    public $menu;

    public $state = [
        'main_dish_id' => null,
        'starter_dish_id' => null,
        'second_dish_id' => null,
        'dessert_id' => null,
    ];

    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->state = $menu->toArray();
    }

    public function saveMenu(UpdateMenuAction $updateMenuAction)
    {
        $this->validate([
            'state.starter_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' => ['nullable', 'required', Rule::exists('dishes', 'id')],
            'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
        ]);

        $updateMenuAction->execute($this->menu, $this->state);

        session()->flash('success', "Le menu a été modifié avec succès!");

        return redirect()->route('menus.index');
    }
    public function render()
    {
        return view('livewire.menus.edit-menu-form', [
            'starter_dishes' => Dish::where('dish_type_id', DishType::STARTER)->pluck('name', 'id'),
            'main_dishes' => Dish::where('dish_type_id', DishType::MAIN)->pluck('name', 'id'),
            'dessert_dishes' => Dish::where('dish_type_id', DishType::DESSERT)->pluck('name', 'id'),
        ]);
    }
}
