<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\PaymentMethod;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\PaymentMethods\UpdatePaymentMethodAction;

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Modification des informations liées au type de d\'utilisateur')
                    ->description('Veuillez saisir des types d\'utilisateurs corrects pour une meilleure affiliation au personnel')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->rules('required', 'max:255'),
                        Textarea::make('description')
                            ->label('Description')
                            ->rules('required', 'max:255'),
                    ])
                // ...
            ])->statePath('state');
    }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('name')
    //             ->label('Nom')
    //             ->required()
    //             ->rules('required', 'max:255'),
    //         Textarea::make('description')
    //             ->label('Description')
    //             ->rules('required', 'max:255'),
    //     ];
    // }

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
