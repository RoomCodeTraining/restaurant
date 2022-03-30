<?php

namespace App\Http\Livewire\AccessCards;

use App\Models\User;
use Livewire\Component;
use App\Models\AccessCard;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use Studio\Totem\Events\Updated;
use App\Events\UpdatedAccessCard;
use App\Actions\AccessCard\LogActionMessage;
use Illuminate\Validation\ValidationException;

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
    $this->user = $user->load('userType.paymentMethod');

    if ($this->user->accessCard) {
      $this->state = [
        'quota_breakfast' => $this->user->accessCard->quota_breakfast,
        'quota_lunch' => $this->user->accessCard->quota_lunch,
      ];
    }

    $paymentMethod = optional($this->user->accessCard)->paymentMethod->name ?? $this->user->userType->paymentMethod->name;
    $this->state['payment_method_id'] = PaymentMethod::firstWhere('name', $paymentMethod)->id;
  }

  public function topUp(LogActionMessage $action)
  {
    //$this->resetErrorBag();

    $this->validate([
      'state.quota_breakfast' => ['required', 'integer', 'min:0', 'max:25'],
      'state.quota_lunch' => ['required', 'integer', 'min:0', 'max:25'],
      'state.payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
    ]);

    
    sleep(2);

    if (!$this->user->accessCard) {
      throw ValidationException::withMessages([
        'state.payment_method_id' => ["Cet utilisateur ne dispose pas de carte RFID associée à son compte."],
      ]);
    }
    
;

    if($this->user->accessCard->quota_lunch != $this->state['quota_lunch']){
        $this->user->accessCard->quota_lunch = $this->state['quota_lunch'];
        $this->user->accessCard->save();
        $action->execute($this->user->accessCard, 'lunch');
    }

    if($this->user->accessCard->quota_breakfast != $this->state['quota_breakfast']){
        $this->user->accessCard->quota_breakfast = $this->state['quota_breakfast'];
        $this->user->accessCard->save();
       $action->execute($this->user->accessCard, 'breakfast');
    }

  

    $selectedPaymentMethod = $this->state['payment_method_id'];

    $this->reset(['state']);

    $this->state['payment_method_id'] = $selectedPaymentMethod;
    session()->flash('success', "Le rechargement a été effectué avec succès.");

    return redirect()->route('users.show', $this->user);
  }

  // Message du log lors du rechargement de carte RFID



  public function render()
  {
    return view('livewire.access-cards.top-up-form', [
      'paymentMethods' => PaymentMethod::pluck('name', 'id')
    ]);
  }
}
