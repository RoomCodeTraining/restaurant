<?php

namespace App\Http\Livewire\Menus;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

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

    // protected function getFormSchema(): array
    // {
    //     return [
    //         \Filament\Forms\Components\Grid::make(1)
    //             ->schema([
    //                 DatePicker::make('state.served_at')
    //                     ->label('Menu du')
    //                     ->columnSpan(2)
    //                     ->minDate(now())
    //                     ->maxDate(now()->addDays(7))
    //                     ->format('d/m/Y'),
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
                DatePicker::make('served_at')
                    ->label('Menu du')
                    ->minDate(now())
                    ->maxDate(now()->addDays(7))
                    ->format('d/m/Y'),
                Select::make('dish_id')
                    ->label('Veuillez choisir le plat')
                    ->options(\App\Models\Dish::pluck('name', 'id')->toArray())
                    ->required(),
            ])->columns(2)
            ->statePath('state');
    }

    public function saveMenu()
    {
        if (\App\Models\MenuSpecial::where('served_at', $this->state['served_at'])->exists()) {
            Notification::make()
                ->title('Menu spécial non créé')
                ->body('Un menu existe déjà pour cette date.')
                ->danger()
                ->send();

            return redirect()->route('menus-specials.create');
        }

        $this->validate([
            'state.served_at' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($value, $attribute, $fail) {
                    if (\App\Models\MenuSpecial::where('served_at', $value)->exists()) {
                        $fail('Un menu existe déjà pour cette date');
                    }
                },
            ],
        ]);

        $menu = \App\Models\MenuSpecial::create([
            'served_at' => $this->state['served_at'],
            'dish_id' => $this->state['dish_id'],
        ]);

        Notification::make()
            ->title('Menu spécial créé')
            ->body('Le menu spécial a été créé avec succès.')
            ->success()
            ->send();

        return redirect()->route('menus-specials.index');
    }

    public function render()
    {
        return view('livewire.menus.create-special-form');
    }
}