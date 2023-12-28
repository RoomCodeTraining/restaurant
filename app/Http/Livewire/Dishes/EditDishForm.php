<?php

namespace App\Http\Livewire\Dishes;

use App\Actions\Dish\UpdateDishAction;
use App\Models\DishType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditDishForm extends Component implements HasForms
{


    use WithFileUploads, InteractsWithForms;

    public ?array $data = [];
    public $dish;

    public function mount($dish): void
    {
        $this->data = $dish->toArray();
        $this->data['image_path'] = null;
        $this->form->fill($this->data);
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Select::make('dish_type_id')
                    ->label('Type de plat')
                    ->required()
                    ->placeholder('Choisissez un type de plat')
                    ->options(DishType::all()->pluck('name', 'id')),
                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Description du plat'),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->placeholder('Selectionnez une image pour ce plat')
            ])
            ->statePath('data');
    }


    public function saveDish(UpdateDishAction $updateDishAction)
    {
        $this->validate([
            'data.name' => ['required', 'string', 'max:255'],
            'data.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.image_path' => ['nullable', 'max:255'],
        ]);

        // store new image if exists

        $updateDishAction->execute($this->dish, $this->data);

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