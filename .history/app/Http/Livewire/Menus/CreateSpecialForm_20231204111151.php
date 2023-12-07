<?php

namespace App\Http\Livewire\Menus;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;


class CreateSpecialForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = [
        'served_at' => null,
        'dish_id' => null,
    ];

    public function mount()
    {
        $this->form->fill($this->state);
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Grid::make(1)
                ->schema([
                    DatePicker::make('state.served_at')
                        ->label('Menu du')
                        ->columnSpan(2)
                        ->minDate(now())
                        ->maxDate(now()->addDays(7))
                        ->format('d/m/Y'),
                    \Filament\Forms\Components\Select::make('state.dish_id')
                        ->label('Veuillez choisir le plat')
                        ->options(\App\Models\Dish::pluck('name', 'id')->toArray())
                        ->required(),
                ]),
        ];
    }

    public function saveMenu()
    {
        if(\App\Models\MenuSpecal::where('served_at', $this->state['served_at'])->exists()){
            Notification::make()
                ->title('Menu spécial non créé')
                ->body("Un menu existe déjà pour cette date.")
                ->danger()
                ->send();
            return redirect()->route('menus-specials.create');
        }

        $this->validate([
            'state.served_at' => ['required', 'date', 'after_or_equal:today', function ($value, $attribute, $fail) {
                if(\App\Models\MenuSpecal::where('served_at', $value)->exists()) {
                    $fail('Un menu existe déjà pour cette date');
                }
            }],
        ]);

        $menu = \App\Models\MenuSpecal::create([
            'served_at' => $this->state['served_at'],
            'dish_id' => $this->state['dish_id'],
        ]);

       Notification::make()
            ->title('Menu spécial créé')
            ->body("Le menu spécial a été créé avec succès.")
            ->success()
            ->send();

        return redirect()->route('menus-specials.index');
    }
    public function render()
    {
        return view('livewire.menus.create-special-form');
    }
}
