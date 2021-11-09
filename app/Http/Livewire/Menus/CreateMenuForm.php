<?php

namespace App\Http\Livewire\Menus;

use App\Models\Dish;
use Livewire\Component;
use App\Actions\Menu\CreateMenuAction;
use Illuminate\Validation\Rule;

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
            'state.main_dish_id' =>  ['required', Rule::exists('dishes', 'id')],
            'state.starter_dish_id' =>  ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' =>  ['required', Rule::exists('dishes', 'id')],
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
            'dishes' => Dish::pluck('name', 'id')
        ]);
    }
}
