<?php

namespace App\Http\Livewire\AccessCards;

use App\Models\AccessCard;
use App\Models\User;
use Livewire\Component;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
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
    $this->user = $user->load('userType.paymentMethod', 'accessCard.paymentMethod');

    if ($this->user->accessCard) {
      $this->state = [
        'quota_breakfast' => $this->user->accessCard->quota_breakfast,
        'quota_lunch' => $this->user->accessCard->quota_lunch,
      ];
    }
    $paymentMethod = optional($this->user->accessCard)->paymentMethod->name ?? $this->user->userType->paymentMethod->name;
    $this->state['payment_method_id'] = PaymentMethod::firstWhere('name', $paymentMethod)->id;
  }

  public function topUp()
  {
    //$this->resetErrorBag();

    $this->validate([
      'state.quota_breakfast' => ['required', 'integer', 'min:0', 'max:25'],
      'state.quota_lunch' => ['required', 'integer', 'min:0', 'max:25'],
      'state.payment_method_id' => ['required', Rule::exists('payment_methods', 'id')],
    ]);


    if (!$this->user->accessCard) {
      throw ValidationException::withMessages([
        'state.payment_method_id' => ["Cet utilisateur ne dispose pas de carte RFID associée à son compte."],
      ]);
    }

    $this->updateCountOfReload($this->user->accessCard);
    $updated = $this->allIsUpdated($this->user->accessCard);
 
    $this->user->accessCard->quota_breakfast =  (int) $this->state['quota_breakfast'];
    $this->user->accessCard->quota_lunch = (int) $this->state['quota_lunch'];
    $this->user->accessCard->payment_method_id = $this->state['payment_method_id'];
    $this->user->accessCard->save();

    $logMessage = $this->logMessage($this->user->accessCard, $updated);

    activity()
      ->causedBy(Auth()->user())
      ->performedOn($this->user->accessCard)
      ->event($logMessage)
      ->log('Rechargement de carte RFID');

    $selectedPaymentMethod = $this->state['payment_method_id'];

    $this->reset(['state']);

    $this->state['payment_method_id'] = $selectedPaymentMethod;
    session()->flash('success', "Le rechargement a été effectué avec succès.");

    return redirect()->route('users.show', $this->user);
  }


  public function updateCountOfReload(AccessCard $accessCard)
  {

    if ($accessCard->quota_lunch != $this->state['quota_lunch']) {
      $accessCard->createReloadHistory('lunch');
    }
    if ($accessCard->quota_breakfast != $this->state['quota_breakfast']) {
      $accessCard->createReloadHistory('breakfast');
    }

    return $accessCard;
  }


  // Message du log lors du rechargement de carte RFID

  public function allIsUpdated(AccessCard $accessCard): string
  {
    if ($accessCard->quota_lunch != $this->state['quota_lunch'] && $accessCard->quota_breakfast != $this->state['quota_breakfast']) {
      return 'all';
    }

    if ($accessCard->quota_lunch != $this->state['quota_lunch']) {
      return 'lunch';
    }

    return 'breakfast';
  }



  public function logMessage(AccessCard $card, string $asUpdated): string
  {
    if ($asUpdated == 'all') {
      return "Les quotas petit déjeuner et déjeuner de " . $card->user->full_name . " ont été rechargées par " . auth()->user()->full_name . " le nouveau quota petit déjeuner est de " . $card->quota_breakfast . " et le nouveau quota déjeuner est de " . $card->quota_lunch;
    }

    if ($asUpdated == 'lunch') {
      return "Le quota déjeuner de " . $card->user->full_name . " a été rechargée par " . auth()->user()->full_name . " et le nouveau quota déjeuner est de " . $card->quota_lunch;
    }

    return "Le quota petit déjeuner de " . $card->user->full_name . " a été rechargée par " . auth()->user()->full_name . " et le nouveau quota petit déjeuner est de " . $card->quota_breakfast;
  }

  public function render()
  {
    return view('livewire.access-cards.top-up-form', [
      'paymentMethods' => PaymentMethod::pluck('name', 'id')
    ]);
  }
}
