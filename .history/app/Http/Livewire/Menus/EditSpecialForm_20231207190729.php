<?php

namespace App\Http\Livewire\Menus;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\MenuSpecal;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class EditSpecialForm extends Component implements HasForms
{
    use InteractsWithForms;

    public MenuSpecal $menuSpecial;
    public $state = [
        'served_at' => null,
        'dish_id' => null,
    ];


    public function mount(): void
    {
        //dd($this->menuSpecial->served_at);
        $this->form->fill([
            'served_at' => $this->menuSpecial->served_at,
            'dish_id' => $this->menuSpecial->dish_id,
        ]);
    }

    // public function getFormSchema(): array
    // {
    //     return [
    //         \Filament\Forms\Components\Grid::make(1)
    //             ->schema([
    //                 \Filament\Forms\Components\DatePicker::make('state.served_at')
    //                     ->label('Menu du')
    //                     ->required(),
    //                 \Filament\Forms\Components\Select::make('state.dish_id')
    //                     ->label('Veuillez choisir le plat')
    //                     ->options(\App\Models\Dish::pluck('name', 'id')->toArray())
    //                     ->required(),
    //             ]),
    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Préférences de commande')
                    ->aside()
                    ->description('Si vous cochez cette case, vous commanderez pour le soir. Sinon, vous commanderez pour le midi')
                    ->schema([
                        DatePicker::make('served_at')
                            ->label('Menu du')
                            ->required(),
                        Select::make('dish_id')
                            ->label('Veuillez choisir le plat')
                            ->options(\App\Models\Dish::pluck('name', 'id')->toArray())
                            ->required(),
                    ]),


                // ...
            ])
            ->statePath('data');
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
