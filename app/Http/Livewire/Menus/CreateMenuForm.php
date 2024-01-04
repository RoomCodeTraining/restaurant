<?php

namespace App\Http\Livewire\Menus;

use App\Models\Dish;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateMenuForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = [
        'main_dishes' => [],
        'side_dishes' => [],
        'served_at' => null,
    ];

    public function saveMenu()
    {
        dd($this->state);
        $this->validate([
            'state.starter_id' => ['required', Rule::exists('dishes', 'id')],
            'state.main_dish_id' => ['required', Rule::exists('dishes', 'id')],
            'state.second_dish_id' => ['nullable', 'integer', 'different:state.main_dish_id', Rule::exists('dishes', 'id')],
            'state.dessert_id' => ['required', Rule::exists('dishes', 'id')],
            'state.served_at' => ['required', 'after:yesterday', Rule::unique('menus', 'served_at')],
        ]);

        // $action->execute($this->state);

        flasher('success', 'Le menu a été crée avec succès.');

        return redirect()->route('menus.index');
    }

    public function messages()
    {
        return [
            'state.served_at.after' => 'Vous ne pouvez pas créer de menu pour une date antérieure.',
            'state.served_at.unique' => 'Un menu existe déjà pour cette date.',
            'state.second_dish_id.different' => 'Le second plat doit être différent du premier plat.',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('served_at')
                    ->label('Menu du')
                    ->minDate(now())
                    ->maxDate(now()->addDays(7))
                    ->format('d/m/Y'),
                Select::make('main_dishes')
                    ->label('Choississez les plats principaux')
                    ->multiple()
                    ->options(Dish::main()->pluck('name', 'id')),
                    Select::make('side_dishes')
                    ->label("Choississez choisir les accompagnements")
                    ->required()
                    ->multiple()
                    ->options(Dish::side()->pluck('name', 'id'))
            ])->columns(2)
            ->statePath('state');
    }

    public function render()
    {
        return view('livewire.menus.create-menu-form');
    }
}