<?php

namespace App\Http\Livewire\AccessCards;

use Livewire\Component;
use App\Models\AccessCard;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use App\Actions\AccessCard\LogActionMessage;

class ReloadCardForm extends Component
{

  public $accessCard, $quota_breakfast, $quota_lunch, $showReloadButton = true, $payment_method_id;


  public function mount(AccessCard $accessCard)
  {
    $this->quota_breakfast = $accessCard->quota_breakfast;
    $this->quota_lunch = $accessCard->quota_lunch;
    $this->accessCard = $accessCard;

    if ($this->accessCard->quota_breakfast != 0 && $this->accessCard->quota_lunch != 0) {
      $this->showReloadButton = false;
    }

    $paymentMethod = optional($accessCard)->paymentMethod->name ?? $this->accessCard->user->userType->paymentMethod->name;
    $this->payment_method_id = PaymentMethod::firstWhere('name', $paymentMethod)->id;
  }


  public function saveQuota(LogActionMessage $action)
  {

    $this->resetErrorBag();

    $this->validate([
      'quota_lunch' => 'required|numeric|min:0|max:25',
      'quota_breakfast' => 'required|numeric|min:0|max:25',
      'payment_method_id' => ['required', Rule::exists('payment_methods', "id")]
    ]);


    if ($this->accessCard->quota_lunch != $this->quota_lunch) {
      $this->accessCard->quota_lunch = $this->quota_lunch;
      $this->accessCard->save();
      $action->execute($this->accessCard, 'lunch');
    }

    if ($this->accessCard->quota_breakfast != $this->quota_breakfast) {
      $this->accessCard->quota_breakfast = $this->quota_breakfast;
      $this->accessCard->save();
      $action->execute($this->accessCard, 'breakfast');
    }

    $this->accessCard->payment_method_id = $this->payment_method_id;
    $this->accessCard->save();

    session()->flash('success', 'Le quota a été rechargé et mis a jour avec succès.');
    return redirect()->route('access-cards.reloads.history');
  }

  public function render()
  {
    return view(
      'livewire.access-cards.reload-card-form',
      [
        'paymentMethods' => PaymentMethod::pluck('name', 'id')
      ]
    );
  }
}
