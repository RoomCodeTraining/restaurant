<?php

namespace App\Http\Livewire\Dishes;

use App\Models\Dish;
use Livewire\Component;
use App\Models\DishType;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Actions\Dish\UpdateDishAction;

class EditDishForm extends Component
{

    use WithFileUploads;

    public $state = [
        'name' => null,
        'description' => null,
        'dish_type_id' => null,
    ];

    public $image_path = null;
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
            'image_path' => ['nullable', 'image:1024', 'max:255'],
        ]);


        $this->state['image_path'] = $this->image_path ? $this->image_path->store('dishes/images') : null;
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
