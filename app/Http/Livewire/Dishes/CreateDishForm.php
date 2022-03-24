<?php

namespace App\Http\Livewire\Dishes;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Actions\Dish\CreateDishAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateDishForm extends Component
{
    use AuthorizesRequests, WithFileUploads;


    public $state = [
        'name' => null,
        'description' => null,
        'dish_type_id' => null,
    ];

    public $image_path = null;

    public function saveDish(CreateDishAction $createDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255', function($attribute, $value, $fail){
                if (\App\Models\Dish::where(['name' => $value, 'dish_type_id' => $this->state['dish_type_id']])->exists()) {
                    $fail('Ce plat existe déjà !');
                }
            }],
            'state.description' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'image:1024', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
        ]);

        
        $this->state['image_path'] = $this->image_path ? $this->image_path->storePublicly('dishes'): null;


        //dd($this->state);
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
