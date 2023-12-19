<?php

namespace App\Http\Livewire\Dishes;

use Livewire\Component;
use App\Models\DishType;
use Filament\Forms\Form;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Select;
use App\Actions\Dish\CreateDishAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateDishForm extends Component implements HasForms
{
    use AuthorizesRequests, WithFileUploads, InteractsWithForms;


    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Select::make('state.dish_type_id')
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

    // public $state = [
    //     'name' => null,
    //     'description' => null,
    //     'dish_type_id' => null,
    //     'image_path' => null,
    // ];

    // // public $image_path = null;


    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('state.name')
    //             ->label('Nom du plat')
    //             ->required()
    //             ->autofocus()
    //             ->placeholder('Salade, choux...'),
    //         Select::make('state.dish_type_id')
    //             ->label('Type de plat')
    //             ->required()
    //             ->placeholder('Choisissez un type de plat')
    //             ->options(DishType::all()->pluck('name', 'id')),
    //         Textarea::make('state.description')
    //             ->label('Description')
    //             ->placeholder('Description du plat'),
    //         FileUpload::make('state.image_path')
    //             ->label('Image')
    //             ->placeholder('Selectionnez une image pour ce plat')

    //     ];
    // }

    public function saveDish(CreateDishAction $createDishAction)
    {
        $p =  $this->validate([
            'state.name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                if (\App\Models\Dish::where(['name' => $value, 'dish_type_id' => $this->state['dish_type_id']])->exists()) {
                    $fail('Ce plat existe déjà !');
                }
            }],
            'state.description' => ['nullable', 'string', 'max:255'],
            'state.image_path' => ['nullable', 'max:255'],
            'state.dish_type_id' => ['required', Rule::exists('dish_types', 'id')],
        ]);



        // store new image if exists
        $image = $this->state['image_path'] ? store_dish_image($this->state['image_path']) : null;
        //dd($image);

        //$this->state['image_path'] = $image;


        //dd($this->state);
        $m = $createDishAction->execute($this->state);

        dd($m);

        Notification::make()
            ->title('Plat ajouté')
            ->body('Le plat a été ajouté avec succès.')
            ->success()->send();

        return redirect()->route('dishes.index');
    }

    public function render()
    {
        return view('livewire.dishes.create-dish-form', [
            'dishTypes' => \App\Models\DishType::pluck('name', 'id'),
        ]);
    }
}
