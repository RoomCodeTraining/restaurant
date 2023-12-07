<?php

namespace App\Http\Livewire\Menus;

use App\Models\Dish;
use Livewire\Component;
use App\Models\DishType;
use App\Support\ActivityHelper;
use Illuminate\Validation\Rule;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Illuminate\Validation\Validator;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use App\Actions\Menu\CreateMenuAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateMenuForm extends Component implements HasForms
{
  use InteractsWithForms;

  public $state = [
    'starter_id' => null,
    'main_dish_id' => null,
    'second_dish_id' => null,
    'third_dish_id' => null,
    'dessert_id' => null,
    'served_at' => null,
  ];

  public function saveMenu(CreateMenuAction $action)
  {
    $this->validate([
      'state.starter_id' => ['required', Rule::exists('dishes', 'id')],
      'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
      'state.second_dish_id' => ['nullable', 'integer', 'different:state.main_dish_id', Rule::exists('dishes', 'id')],
      'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
      'state.served_at' => ['required', 'after:yesterday', Rule::unique('menus', 'served_at')],
    ]);


    $action->execute($this->state);

    flasher('success', "Le menu a été crée avec succès.");

    return redirect()->route('menus.index');
  }


  public function messages()
  {
    return [
      'state.served_at.after' => "Vous ne pouvez pas créer de menu pour une date antérieure.",
      'state.served_at.unique' => "Un menu existe déjà pour cette date.",
      'state.second_dish_id.different' => "Le second plat doit être différent du premier plat."
    ];
  }


  protected function getFormSchema(): array
  {
    return [
      Grid::make(2)
      ->schema([

        DatePicker::make('state.served_at')
          ->label('Menu du')
          ->columnSpan(2)
          ->minDate(now())
          ->maxDate(now()->addDays(7))
          ->format('d/m/Y'),
        Select::make('state.starter_id')
        ->label("Choississez l'entrée")
        ->required()
        ->options(Dish::starter()->pluck('name', 'id')),
        Select::make('state.dessert_id')
        ->label("Choississez le dessert")
        ->required()
        ->options(Dish::dessert()->pluck('name', 'id')),
        Select::make('state.main_dish_id')
        ->label("Choississez le plat principal 1")
        ->required()
        ->options(Dish::main()->pluck('name', 'id')),
        Select::make('state.second_dish_id')
        ->label("Choississez le plat principal 2")
        ->options(Dish::main()->pluck('name', 'id')),
      ])


    ];
  }

  public function render()
  {
    return view('livewire.menus.create-menu-form', [
      'starter_dishes' => Dish::starter()->pluck('name', 'id'),
      'main_dishes' => Dish::main()->pluck('name', 'id'),
      'dessert_dishes' => Dish::dessert()->pluck('name', 'id'),
    ]);
  }
}
