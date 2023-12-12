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
        return $form->schema([

            Section::make('Rate limiting')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->aside()
                ->schema([
                    // ...
                ])
        ])->statePath('data');
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