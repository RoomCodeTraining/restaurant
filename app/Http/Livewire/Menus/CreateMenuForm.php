<?php

namespace App\Http\Livewire\Menus;

use App\Actions\Menu\CreateMenuAction;
use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateMenuForm extends Component
{
    public $state = [
        'main_dish_id' => null,
        'starter_dish_id' => null,
        'second_dish_id' => null,
        'dessert_id' => null,
        'served_at' => null,
    ];

    public function saveMenu(CreateMenuAction $action)
    {
        $this->validate([
            'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.starter_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' => ['nullable', 'integer', Rule::exists('dishes', 'id')],
            'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
            'state.served_at' => ['required', 'date', Rule::unique('menus', 'served_at')],
        ]);

        $action->execute($this->state);

        session()->flash('success', "Le menu a été créé avec succès!");

        return redirect()->route('menus.index');
    }

    public function render()
    {
        return view('livewire.menus.create-menu-form', [
            'starter_dishes' => Dish::where('dish_type_id', DishType::STARTER)->pluck('name', 'id'),
            'main_dishes' => Dish::where('dish_type_id', DishType::MAIN)->pluck('name', 'id'),
            'dessert_dishes' => Dish::where('dish_type_id', DishType::DESSERT)->pluck('name', 'id'),
        ]);
    }
}
