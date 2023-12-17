<?php

namespace App\Http\Livewire\AccessCards;

use App\Actions\AccessCard\LogActionMessage;
use App\Models\AccessCard;
use App\Models\PaymentMethod;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Livewire\Component;

class ReloadCardForm extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    public $accessCard;
    public $showReloadButton = true;
    public $state = [
        'quota_breakfast' => '',
        'quota_lunch' => '',
        'payment_method_id' => '',
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

    public function accessCardInfo(Infolist $infolist): Infolist
    {
        return $infolist->record($this->accessCard)
        ->schema([
            Section::make('Informations de la carte')
                ->headerActions([
                // Action::make('add_breakfast')->label('Petit déjeuner'),
                Action::make('edit')->label('Déjeuner')
                      ->requiresConfirmation()
                      ->action(function (AccessCard $accessCard) {
                          dd($accessCard);
                      })
                    // ->action(function (AccessCard $accessCard) {
                    //     dd($accessCard);
                    // }),
            ])
            ->schema([
                TextEntry::make('identifier')->label('Identifiant'),
                 TextEntry::make('type')->label('Type'),
                 TextEntry::make('quota_lunch')->label('Quota déjeuner')->hintAction(
                     Action::make('edit')
                     ->label('Sourche')
                     ->tooltip('Attribuer un quota de déjeuner')
                     ->icon('heroicon-o-plus-circle')
                        ->hidden(fn (AccessCard $accessCard) => ! $accessCard->canBeReloaded('quota_lunch'))
                      ->action(function (AccessCard $accessCard) {
                          $oldQuota = $accessCard->quota_lunch;
                          $newQuota = $oldQuota + config('cantine.quota_lunch');
                          (new LogActionMessage())->execute($accessCard, 'lunch');
                          $accessCard->update(['quota_lunch' => $newQuota]);
                          Notification::make()
                            ->title('Quota mis à jour')
                            ->body('Le quota de la carte a été mis à jour avec succès.')
                            ->success()
                            ->send();
                      })
                 ),
                  TextEntry::make('quota_breakfast')
                    ->label('Quota petit déjeuner')
                    ->hintAction(
                        Action::make('add_breakfast_quota')
                        ->label('Sourche')
                        ->tooltip('Attribuer un quota de petit déjeuner')
                        ->icon('heroicon-o-plus-circle')
                        ->hidden(fn (AccessCard $accessCard) => ! $accessCard->canBeReloaded('quota_breakfast'))
                        ->action(function (AccessCard $accessCard) {
                            $oldQuota = $accessCard->quota_breakfast;
                            $newQuota = $oldQuota + config('cantine.quota_breakfast');
                            (new LogActionMessage())->execute($accessCard, 'breakfast');
                            $accessCard->update(['quota_breakfast' => $newQuota]);

                            Notification::make()
                                ->title('Quota mis à jour')
                                ->body('Le quota de la carte a été mis à jour avec succès.')
                                ->success()
                                ->send();
                        })
                    ),
            ])->columns(2)
        ]);
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

        Notification::make()
            ->title('Quota mis à jour')
            ->body('Le quota de la carte a été mis à jour avec succès.')
            ->success()
            ->send();

        return redirect()->route('access-cards.reloads.history');
    }

    public function render()
    {
        return view('livewire.access-cards.reload-card-form', [
            'paymentMethods' => PaymentMethod::pluck('name', 'id'),
        ]);
    }
}