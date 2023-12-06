<?php

namespace App\Http\Livewire\Account;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class OrderConfigForm extends Component implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    public ?array $data = [];


    public function mount()
    {
        $this->data = [
            'is_for_evening' => auth()->user()->order_for_evening,
        ];
    }

    public function form(Form $form): Form
    {
        // return $form
        //     ->schema([
        //    Section::make('Préférences de commande')
        //        ->aside()
        //        ->description('Si vous cochez cette case, vous commanderez pour le soir. Sinon, vous commanderez pour le midi')
        //        ->schema([
        //             Toggle::make('is_for_evening')
        //                 ->label('Commander pour le soir')
        //        ])

        //    // ...
        //     ])
        //     ->statePath('data');

        return $form
            ->schema([
                Section::make('Modifier mon compte')
                    ->aside()
                    ->description('Vous pouvez mettre à jour vos informations de compte')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('first_name')->label('Nom'),
                            TextInput::make('last_name')->label('Prénoms'),
                            TextInput::make('email')->label('Adresse e-mail'),
                            TextInput::make('phone')->label('Contact')->tel()
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),

                            TextInput::make('country')->label('Pays')->hidden(auth()->user()->isAdmin() || auth()->user()->isCustomerManager()),
                            TextInput::make('city')->label('Ville')->hidden(auth()->user()->isAdmin() || auth()->user()->isCustomerManager()),
                            DatePicker::make('birthday_at')->label('Date de naissance')->hidden(auth()->user()->isAdmin() || auth()->user()->isCustomerManager())
                        ])
                    ])

                // ...
            ])
            ->statePath('data');
    }

    public function save()
    {
        $this->validate();
        auth()->user()->update([
            'order_for_evening' => $this->data['is_for_evening'],
        ]);

        Notification::make()
            ->title('Préférences de commande')
            ->body('Vos préférences de commande ont été mises à jour avec succès.')
            ->success()
            ->send();

        return redirect()->route('profile');
    }

    public function render()
    {
        return view('livewire.account.order-config-form');
    }
}
