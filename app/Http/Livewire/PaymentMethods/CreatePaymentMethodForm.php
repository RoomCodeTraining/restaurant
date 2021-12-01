<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Actions\PaymentMethods\CreatePaymentMethodAction;

class CreatePaymentMethodForm extends Component
{
    public $state = [
        'name' => null,
        'description' => null,
    ];



    public function savePaymentMethod(CreatePaymentMethodAction $action){
        $this->validate([
            'state.name' => ['required', Rule::unique('payment_methods', 'name'), Rule::unique('payment_methods', 'id')]
        ]);
        $action->execute($this->state);
        session()->flash('success', 'La méthode de paiement a été créee !');
        return redirect()->route('paymentMethods.index');
    }

    public function render()
    {
        return view('livewire.payment-methods.create-payment-method-form');
    }
}
