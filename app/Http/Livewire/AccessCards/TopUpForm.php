<?php

namespace App\Http\Livewire\AccessCards;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TopUpForm extends Component
{
    public $user;

    public $state = [
        'quota_breakfast' => 0,
        'quota_lunch' => 0,
        'payment_method_id' => null,
    ];

    public function mount(User $user)
    {
        $this->user = $user->load('userType.paymentMethod', 'accessCard.paymentMethod');
        $paymentMethod = optional($this->user->accessCard)->paymentMethod->name ?? $this->user->userType->paymentMethod->name;
        $this->state['payment_method_id'] = PaymentMethod::firstWhere('name', $paymentMethod)->id;
    }

    public function topUp()
    {
        $this->resetErrorBag();

        $this->validate([
            'state.quota_breakfast' => ['required', 'integer', 'min:0', 'max:25', function ($attribute, $value, $fail) {
                $maxAvailableQuota = config('cantine.quota_breakfast') - $this->user->accessCard->quota_breakfast;

                if ($this->user->accessCard && $value > $maxAvailableQuota) {
                    $fail(sprintf("Vous ne pouvez recharger plus de %s pour ce compte.", $maxAvailableQuota));
                }
            }],
            'state.quota_lunch' => ['required', 'integer', 'min:0', 'max:25', function ($attribute, $value, $fail) {
                $maxAvailableQuota = config('cantine.quota_lunch') - $this->user->accessCard->quota_lunch;

                if ($this->user->accessCard && $value > $maxAvailableQuota) {
                    $fail(sprintf("Vous ne pouvez recharger plus de %s pour ce compte.", $maxAvailableQuota));
                }
            }],
            'state.payment_method_id' => ['required'],
        ]);

        if (! $this->user->accessCard) {
            throw ValidationException::withMessages([
                'state.payment_method_id' => ["Ce utilisateur ne dispose pas de carte RFID associé à son compte."],
            ]);
        }

        $this->user->accessCard->quota_breakfast = $this->user->accessCard->quota_breakfast + (int) $this->state['quota_breakfast'];
        $this->user->accessCard->quota_lunch = $this->user->accessCard->quota_lunch + (int) $this->state['quota_lunch'];
        $this->user->accessCard->payment_method_id = $this->state['payment_method_id'];
        $this->user->accessCard->save();

        $selectedPaymentMethod = $this->state['payment_method_id'];

        $this->reset(['state']);

        $this->state['payment_method_id'] = $selectedPaymentMethod;

        session()->flash('success', "Le rechargement a été effectué avec succès.");

        return redirect()->route('users.show', $this->user);
    }

    public function render()
    {
        return view('livewire.access-cards.top-up-form', [
            'paymentMethods' => PaymentMethod::pluck('name', 'id')
        ]);
    }
}
