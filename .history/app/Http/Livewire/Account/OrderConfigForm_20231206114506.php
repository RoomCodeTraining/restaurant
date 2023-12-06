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
        return $form

            ->schema([
                Section::make('Mot de passe')
                    ->aside()
                    //->description('Mettre à jour les informations de votre compte. Pour des raisons de sécurité, votre mot de passe doit contenir au moins 8 caractères, dont au moins une lettre majuscule, une lettre minuscule et un chiffre.')
                    ->schema([
                        // Grid::make(1)->schema([
                        //     TextInput::make('current_password')
                        //         ->type('password')
                        //         ->label('Mot de passe actuel'),
                        //     TextInput::make('password')
                        //         ->type('password')
                        //         ->label('Mot de passe'),
                        //     TextInput::make('password_confirmation')
                        //         ->type('password')
                        //         ->label('Confirmer votre mot de passe'),
                        // ])
                    ])

                // ...
            ])
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
