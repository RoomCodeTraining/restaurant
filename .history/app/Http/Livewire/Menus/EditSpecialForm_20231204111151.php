<?php

namespace App\Http\Livewire\Menus;

use Livewire\Component;
use App\Models\MenuSpecal;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class EditSpecialForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $menuSpecial;
    public $state = [
        'served_at' => '',
        'dish_id' => '',
    ];


    public function mount(MenuSpecal $menuSpecial)
    {
        $this->menuSpecial = $menuSpecial;
        $this->state = $this->menuSpecial->toArray();
        $this->form->fill($this->state);
    }

    public function getFormSchema(): array
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

    public function saveMenu()
    {
        $this->validate([
            'state.served_at' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $this->menuSpecial->update([
            'served_at' => $this->state['served_at'],
            'dish_id' => $this->state['dish_id'],
        ]);

        flasher("success", "Le menu a été modifié avec succès.");
        return redirect()->route('menus-specials.index');
    }
    public function render()
    {
        return view('livewire.menus.edit-special-form');
    }
}
