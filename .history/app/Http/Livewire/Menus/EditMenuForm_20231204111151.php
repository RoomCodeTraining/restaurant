<?php

namespace App\Http\Livewire\Menus;

use App\Models\Dish;
use App\Models\Menu;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Notifications\MenuChanged;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use App\Actions\Menu\UpdateMenuAction;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Notification;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class EditMenuForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $menu;

    public $state = [
        'starter_id' => null,
        'main_dish_id' => null,
        'second_dish_id' => null,
        'dessert_id' => null,
    ];

    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->state = [
            'starter_id' => $menu->starter->id,
            'main_dish_id' => $menu->main_dish->id,
            'second_dish_id' => $menu->second_dish?->id,
            'dessert_id' => $menu->dessert->id,
        ];
    }

    public function getFormSchema() : array
    {
        return [
              Grid::make(2)
      ->schema([
        DateTimePicker::make('state.served_at')
        ->displayFormat('d/m/Y')
        ->label('Menu du')
        ->columnSpan(2)
        ->required()
        ->autofocus()
        ->placeholder('Jour...'),
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

    public function saveMenu(UpdateMenuAction $action)
    {
        $this->validate([
            'state.starter_id' => ['required', Rule::exists('dishes', 'id')],
            'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' => ['nullable', 'integer', Rule::exists('dishes', 'id'), function($attr, $value, $fail){
                if($this->state['second_dish_id'] && $this->state['second_dish_id'] == $this->state['main_dish_id']){
                    return $fail('Le second plat ne peut pas être le même que le plat principal !');
                }
            }],
            'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
        ]);

        $menu = $action->execute($this->menu, $this->state);

        Notification::send(
            User::query()->whereRelation('orders', 'menu_id', $menu->id)->get(),
            new MenuChanged($action->execute($this->menu, $this->state))
        );

        flasher("success", "Le menu a été modifié avec succès.");
        return redirect()->route('menus.index');
    }

    public function messages()
    {
        return [
            'state.second_dish_id.different' => "Le second plat doit être différent du premier plat."
        ];
    }

    public function render()
    {
        return view('livewire.menus.edit-menu-form', [
            'starter_dishes' => Dish::starter()->pluck('name', 'id'),
            'main_dishes' => Dish::main()->pluck('name', 'id'),
            'dessert_dishes' => Dish::dessert()->pluck('name', 'id'),
        ]);
    }
}
