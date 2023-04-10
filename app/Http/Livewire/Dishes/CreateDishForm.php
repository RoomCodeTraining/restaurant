<?php

namespace App\Http\Livewire\Dishes;

use Livewire\Component;
use App\Models\DishType;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Select;
use App\Actions\Dish\CreateDishAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateDishForm extends Component implements HasForms
{
    use AuthorizesRequests, WithFileUploads, InteractsWithForms;


    public $state = [
        'name' => null,
        'description' => null,
        'dish_type_id' => null,
    ];

    public $image_path = null;


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
            ->required()
            ->placeholder('Description du plat'),

          FileUpload::make('image_path')
            ->label('Image')
            ->required()
            ->placeholder('Selectionnez une image pour ce plat')

      ];
    }

    public function saveDish(CreateDishAction $createDishAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'max:255', function($attribute, $value, $fail){
                if (\App\Models\Dish::where(['name' => $value, 'dish_type_id' => $this->state['dish_type_id']])->exists()) {
                    $fail('Ce plat existe déjà !');
                }
            }],
            'state.description' => ['nullable', 'string', 'max:255'],
            'image_path' => ['nullable', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
        ]);


        foreach($this->image_path as $image){
          $this->state['image_path'] =  $image->store('dishes') ?? null;
        }


        //dd($this->state);
        $createDishAction->execute($this->state);

        flasher('success', 'Le plat a été ajouté avec succès !');
        return redirect()->route('dishes.index');
    }

    public function render()
    {
        return view('livewire.dishes.create-dish-form', [
            'dishTypes' => \App\Models\DishType::pluck('name', 'id'),
        ]);
    }
}
