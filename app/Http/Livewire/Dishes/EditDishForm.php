<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\UpdateDishAction;
use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditDishForm extends Component
{
    public $state = [
        'name' => null,
        'description' => null,
        'dish_type_id' => null,
    ];

    public $dish;


    public function mount(Dish $dish)
    {
        $this->user = $dish;
        $this->state = $dish->toArray();
    }

    public function saveDish(UpdateDishAction $updateDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $updateDishAction->execute($this->dish, $this->state);

        session()->flash('success', "Le plat a été modifié avec succès!");

        return redirect()->route('dishes.index');
    }


    public function render()
    {
        return view('livewire.dishes.edit-dish-form', [
            'dishTypes' => DishType::pluck('name', "id"),
        ]);
    }
}
