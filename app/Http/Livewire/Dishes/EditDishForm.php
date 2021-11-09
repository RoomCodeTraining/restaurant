<?php

namespace App\Http\Livewire\Dishes;

use App\Models\Dish;
use Livewire\Component;
use App\Models\DishType;
use Illuminate\Validation\Rule;
use App\Actions\Dish\UpdateDishAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

      public function messages()
    {
        return [
            'required' => 'Cette valeur est requise',
            'string' => 'Cette valeur doit etre une chaine de caractere',
            'email' => 'Cette valeur doit etre une adresse email',
            'max' => 'Cette valeur est trop grande',
            'min' => 'Cette valeur est trop petite',
        ];
    }


    public function saveDish(UpdateDishAction $updateDishAction)
    {

       $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.description' => ['required', 'string', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
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
