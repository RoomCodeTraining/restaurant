<?php

namespace App\Http\Livewire\Menus;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateSpecialForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = [
        'served_at' => null,
        'dish_id' => null,
    ];

    public function mount(){
        $this->form->fill($this->state);
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Grid::make(1)
                ->schema([
                    \Filament\Forms\Components\DatePicker::make('state.served_at')
                        ->label('Menu du')
                        ->required(),
                    \Filament\Forms\Components\Select::make('state.dish_id')
                        ->label('Veuillez choisir le plat')
                        ->options(\App\Models\Dish::pluck('name', 'id')->toArray())
                        ->required(),
                ]),
        ];
    }

    public function saveMenu(){
        $this->validate([
            'state.served_at' => ['required', 'date', 'after_or_equal:today', function($value, $attribute, $fail){
                if(\App\Models\Menu::where('served_at', $value)->exists()){
                    $fail('Un menu existe déjà pour cette date');
                }
            }],
        ]);

        $menu = \App\Models\MenuSpecal::create([
            'served_at' => $this->state['served_at'],
            'dish_id' => $this->state['dish_id'],
        ]);

        flasher("success", "Le menu a été crée avec succès.");
        return redirect()->route('menus-specials.index');
    }
    public function render()
    {
        return view('livewire.menus.create-special-form');
    }
}
