<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\CreateDishAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateDishForm extends Component
{
    use AuthorizesRequests;


    public $state = [
        'name' => null,
        'description' => null,
        'dish_type_id' => null,
    ];

    public function saveDish(CreateDishAction $createDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.description' => ['nullable', 'string', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
        ]);

        $createDishAction->execute($this->state);

        session()->flash('success', "Le plat a été créé avec succès!");

        return redirect()->route('dishes.index');
    }

    public function render()
    {
        return view('livewire.dishes.create-dish-form', [
            'dishTypes' => \App\Models\DishType::pluck('name', 'id'),
        ]);
    }
}
