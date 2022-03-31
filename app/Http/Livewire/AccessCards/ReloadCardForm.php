<?php

namespace App\Http\Livewire\AccessCards;

use Livewire\Component;
use App\Models\AccessCard;
use App\Actions\AccessCard\LogActionMessage;

class ReloadCardForm extends Component
{

    public $accessCard, $quota_breakfast, $quota_lunch, $showReloadButton = true;

    public function mount(AccessCard $accessCard)
    {
        $this->quota_breakfast = $accessCard->quota_breakfast;
        $this->quota_lunch = $accessCard->quota_lunch;
        $this->accessCard = $accessCard;

        if($this->accessCard->quota_breakfast != 0 && $this->accessCard->quota_lunch != 0) {
            $this->showReloadButton = false;
        }
    }


    public function saveQuota(LogActionMessage $action){

      $this->resetErrorBag();

      $this->validate([
        'quota_lunch' => 'required|numeric|min:0|max:25',
        'quota_breakfast' => 'required|numeric|min:0|max:25',
      ]);

      if($this->accessCard->quota_lunch != $this->quota_lunch){
        $this->accessCard->quota_lunch = $this->quota_lunch;
        $this->accessCard->save();
        $action->execute($this->accessCard, 'lunch');
    }

    if($this->accessCard->quota_breakfast != $this->quota_breakfast){
        $this->accessCard->quota_breakfast = $this->quota_breakfast;
        $this->accessCard->save();
       $action->execute($this->accessCard, 'breakfast');
    }

    session()->flash('message', 'Le quota a été rechargé avec succès.');
    return redirect()->route('access-cards.reloads.history');

    
    }

    public function render()
    {
        return view('livewire.access-cards.reload-card-form');
    }
}
