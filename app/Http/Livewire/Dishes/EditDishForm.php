<?php

namespace App\Http\Livewire\Dishes;

use App\Models\Dish;
use Livewire\Component;
use App\Models\DishType;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Select;
use App\Actions\Dish\UpdateDishAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

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
              ->rules('image')
              ->placeholder('Selectionnez une image pour ce plat')

        ];
    }


    public function saveDish(UpdateDishAction $updateDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
            'state.description' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'max:255'],
        ]);

        // store new image if exists
        $image = $this->image_path ? store_dish_image($this->image_path) : $this->dish->image_path;

        $this->state['image_path'] = $image;
        $updateDishAction->execute($this->dish, $this->state);

       Notification::make()->title('Mise à jour du plat')->body('Le plat a été mis à jour avec succès.')->success()->send();

        return redirect()->route('dishes.index');
    }




    public function render()
    {
        return view('livewire.dishes.edit-dish-form', [
            'dishTypes' => DishType::pluck('name', "id"),
        ]);
    }
}
