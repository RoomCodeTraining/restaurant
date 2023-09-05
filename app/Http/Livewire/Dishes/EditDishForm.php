<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\UpdateDishAction;
use App\Models\Dish;
use App\Models\DishType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditDishForm extends Component implements HasForms
{


    use WithFileUploads, InteractsWithForms;

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



    protected function getFormSchema(): array
    {
        return [
           TextInput::make('state.name')
              ->label('Nom du plat')
              ->required()
              ->autofocus()
              ->placeholder('Salade, choux...'),
              Select::make('state.dish_type_id')
              ->label('Type de plat')
              ->required()
              ->placeholder('Choisissez un type de plat')
              ->options(DishType::all()->pluck('name', 'id')),
            Textarea::make('state.description')
              ->label('Description')
              ->placeholder('Description du plat'),

            FileUpload::make('image_path')
              ->label('Image')
              ->required()
              ->placeholder('Selectionnez une image pour ce plat')

        ];
    }


    public function saveDish(UpdateDishAction $updateDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
            'state.description' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'image:1024', 'max:255'],
        ]);


        $this->state['image_path'] = $this->image_path ? $this->image_path->storePublicly('dishes'): null;
        $updateDishAction->execute($this->dish, $this->state);

        flasher('success', 'Le plat a bien été modifié.');

        return redirect()->route('dishes.index');
    }


    public function render()
    {
        return view('livewire.dishes.edit-dish-form', [
            'dishTypes' => DishType::pluck('name', "id"),
        ]);
    }
}
