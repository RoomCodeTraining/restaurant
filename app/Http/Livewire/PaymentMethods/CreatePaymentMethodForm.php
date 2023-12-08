<?php

namespace App\Http\Livewire\PaymentMethods;

use App\Actions\PaymentMethods\CreatePaymentMethodAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePaymentMethodForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $state = [
        'name' => null,
        'description' => null,
    ];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->rules('required', 'max:255'),
                Textarea::make('description')
                    ->label('Description')
                    ->rules('required', 'max:255'),
                // ...
            ])
            ->statePath('state');
    }

    public function savePaymentMethod(CreatePaymentMethodAction $action)
    {
        $this->validate([
            'state.name' => ['required', Rule::unique('payment_methods', 'name'), Rule::unique('payment_methods', 'id')],
        ]);
        $action->execute($this->state);

        flasher('success', 'Le moyen de paiement a bien été créé.');

        return redirect()->route('paymentMethods.index');
    }

    public function render()
    {
        return view('livewire.payment-methods.create-payment-method-form');
    }
}