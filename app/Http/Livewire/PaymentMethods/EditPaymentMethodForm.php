<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use App\Models\PaymentMethod;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\PaymentMethods\UpdatePaymentMethodAction;
use Filament\Notifications\Notification;

class EditPaymentMethodForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $state = [
        'name' => null,
        'description' => null,
    ];

    public $paymentMethod;

    public function mount(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->form->fill([
            'state.name' => $paymentMethod->name,
            'state.description' => $paymentMethod->description,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('state.name')
                ->label('Nom')
                ->required()
                ->rules('required', 'max:255'),
            Textarea::make('state.description')
                ->label('Description')
                ->rules('required', 'max:255'),
        ];
    }

    public function savePaymentMethod(UpdatePaymentMethodAction $action)
    {
        $this->validate([
            'state.name' => ['required']
        ]);

        $action->execute($this->paymentMethod, $this->state);


        flasher("success", "Le moyen de paiement a bien été modifié.");

        return redirect()->route('paymentMethods.index');
    }

    public function render()
    {
        return view('livewire.payment-methods.edit-payment-method-form');
    }
}
