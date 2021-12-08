<?php

namespace App\Http\Livewire\PaymentMethods;

use App\Actions\PaymentMethods\UpdatePaymentMethodAction;
use App\Models\PaymentMethod;
use Livewire\Component;

class EditPaymentMethodForm extends Component
{
    public $state = [
        'name' => null,
        'description' => null,
    ];

    public $paymentMethod;

    public function mount(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->state = [
            'name' => $paymentMethod->name,
            'description' => $paymentMethod->description,
        ];
    }

    public function savePaymentMethod(UpdatePaymentMethodAction $action)
    {
        $this->validate([
            'state.name' => ['required']
        ]);

        $action->execute($this->paymentMethod, $this->state);
        session()->flash('success', 'La méthode de paiement a été modifiée !');

        return redirect()->route('paymentMethods.index');
    }

    public function render()
    {
        return view('livewire.payment-methods.edit-payment-method-form');
    }
}
