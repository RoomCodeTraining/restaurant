<?php

namespace App\Http\Livewire\PaymentMethods;

use App\Actions\PaymentMethods\UpdatePaymentMethodAction;
use App\Models\PaymentMethod;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class EditPaymentMethodForm extends Component implements HasForms
{
    use InteractsWithForms;
    public PaymentMethod $paymentMethod;

    public ?array $datInteractsWithFormsa = [];

    public $state = [
        'name' => null,
        'description' => null,
    ];


    public function mount(): void
    {
        $this->form->fill([
            'name' => $this->paymentMethod->name,
            'description' => $this->paymentMethod->description,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->rules('required', 'max:255'),
            Textarea::make('description')
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
