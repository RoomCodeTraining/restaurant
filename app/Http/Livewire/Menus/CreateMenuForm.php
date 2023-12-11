<?php

namespace App\Http\Livewire\Menus;

use App\Actions\Menu\CreateMenuAction;
use App\Models\Dish;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
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

    // protected function getFormSchema(): array
    // {
    //     return [
    //         Grid::make(2)
    //             ->schema([

    //                 DatePicker::make('state.served_at')
    //                     ->label('Menu du')
    //                     ->columnSpan(2)
    //                     ->minDate(now())
    //                     ->maxDate(now()->addDays(7))
    //                     ->format('d/m/Y'),
    //                 Select::make('state.starter_id')
    //                     ->label("Choississez l'entrée")
    //                     ->required()
    //                     ->options(Dish::starter()->pluck('name', 'id')),
    //                 Select::make('state.dessert_id')
    //                     ->label("Choississez le dessert")
    //                     ->required()
    //                     ->options(Dish::dessert()->pluck('name', 'id')),
    //                 Select::make('state.main_dish_id')
    //                     ->label("Choississez le plat principal 1")
    //                     ->required()
    //                     ->options(Dish::main()->pluck('name', 'id')),
    //                 Select::make('state.second_dish_id')
    //                     ->label("Choississez le plat principal 2")
    //                     ->options(Dish::main()->pluck('name', 'id')),
    //             ])

    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('served_at')
                    ->label('Menu du')
                    ->minDate(now())
                    ->maxDate(now()->addDays(7))
                    ->format('d/m/Y'),
                Select::make('starter_id')
                    ->label("Choississez l'entrée")
                    ->required()
                    ->options(Dish::starter()->pluck('name', 'id')),
                Select::make('dessert_id')
                    ->label('Choississez le dessert')
                    ->required()
                    ->options(Dish::dessert()->pluck('name', 'id')),
                Select::make('main_dish_id')
                    ->label('Choississez le plat principal 1')
                    ->required()
                    ->options(Dish::main()->pluck('name', 'id')),
                Select::make('second_dish_id')
                    ->label('Choississez le plat principal 2')
                    ->options(Dish::main()->pluck('name', 'id')),
            ])->columns(2)
            ->statePath('state');
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