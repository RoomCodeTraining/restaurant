<?php

namespace App\Http\Livewire\AccessCards;

use App\Actions\AccessCard\LogActionMessage;
use App\Models\AccessCard;
use App\Models\PaymentMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class ReloadCardForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $accessCard;
    public $showReloadButton = true;
    public $state = [
      'quota_breakfast' => '',
      'quota_lunch' => '',
      'payment_method_id' => ''
    ];

    public function mount(AccessCard $accessCard)
    {
        $this->state['quota_breakfast'] = $accessCard->quota_breakfast;
        $this->state['quota_lunch'] = $accessCard->quota_lunch;
        $this->accessCard = $accessCard;
        $this->form->fill($this->state);

        if ($this->accessCard->quota_breakfast != 0 && $this->accessCard->quota_lunch != 0) {
            $this->showReloadButton = false;
        }

        $paymentMethod = optional($accessCard)->paymentMethod->name ?? $this->accessCard->user->userType->paymentMethod->name;
        $this->state['payment_method_id'] = PaymentMethod::firstWhere('name', $paymentMethod)->id;
    }

    public function getFormSchema() : array
    {
        return [
          TextInput::make('state.quota_breakfast')->rules('required|numeric|min:0|max:25')->label('Quota petit déjeuner'),
          TextInput::make('state.quota_lunch')->rules('required|numeric|min:0|max:25')->label('Quota déjeuner'),
          Select::make('state.payment_method_id')->options(PaymentMethod::pluck('name', 'id'))->label('Moyen de paiement')
        ];
    }


    public function saveQuota(LogActionMessage $action)
    {

        $this->resetErrorBag();

        $this->validate();


        if ($this->accessCard->quota_lunch != $this->state['quota_lunch']) {
            $this->accessCard->quota_lunch = $this->state['quota_lunch'];
            $this->accessCard->save();
            $action->execute($this->accessCard, 'lunch');
        }

        if ($this->accessCard->quota_breakfast != $this->state['quota_breakfast']) {
            $this->accessCard->quota_breakfast = $this->state['quota_breakfast'];
            $this->accessCard->save();
            $action->execute($this->accessCard, 'breakfast');
        }

        $this->accessCard->payment_method_id = $this->state['payment_method_id'];
        $this->accessCard->save();

        Notification::make()->title('Quota mis à jour')->body('Le quota de la carte a été mis à jour avec succès.')->success()->send();

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