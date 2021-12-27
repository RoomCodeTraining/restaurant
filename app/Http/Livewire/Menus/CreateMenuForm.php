<?php

namespace App\Http\Livewire\Menus;

use App\Actions\Menu\CreateMenuAction;
use App\Models\Dish;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateMenuForm extends Component
{
    public $state = [
        'starter_id' => null,
        'main_dish_id' => null,
        'second_dish_id' => null,
        'dessert_id' => null,
        'served_at' => null,
    ];

    public function saveMenu(CreateMenuAction $action)
    {
        $this->validate([
            'state.starter_id' => ['required', Rule::exists('dishes', 'id')],
            'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' => ['nullable', 'integer', 'different:state.main_dish_id', Rule::exists('dishes', 'id')],
            'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
            'state.served_at' => ['required', 'after:yesterday', Rule::unique('menus', 'served_at')],
        ]);

        $action->execute($this->state);

        session()->flash('success', "Le menu a été créé avec succès!");

        return redirect()->route('menus.index');
    }

    public function messages()
    {
        return [
            'state.served_at.after' => "Vous ne pouvez pas créer de menu pour une date antérieure.",
            'state.served_at.unique' => "Un menu existe déjà pour cette date.",
            'state.second_dish_id.different' => "Le second plat doit être différent du premier plat."
        ];
    }

    public function render()
    {
        return view('livewire.menus.create-menu-form', [
            'starter_dishes' => Dish::starter()->pluck('name', 'id'),
            'main_dishes' => Dish::main()->pluck('name', 'id'),
            'dessert_dishes' => Dish::dessert()->pluck('name', 'id'),
        ]);
    }
}
