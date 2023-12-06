<?php

namespace App\Http\Livewire\PaymentMethods;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\PaymentMethods\CreatePaymentMethodAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class CreatePaymentMethodForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $state = [
        'name' => null,
        'description' => null,
    ];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ajout d\'une nouvelle catégorie professionnelle')
                    ->description('Veuillez saisir des noms de catégorie professionnelle corrects pour une meilleure affiliation')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->rules('required', 'max:255', 'unique:employee_statuses,name'),

                    ])
                // ...
            ])->statePath('state');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('state.name')
                ->label('Nom')
                ->required()
                ->rules('required', 'max:255'),
            Textarea::make('state.description')
                ->label('Description')
                ->rules('required', 'max:255'),
        ];
    }

    public function savePaymentMethod(CreatePaymentMethodAction $action)
    {
        $this->validate([
            'state.name' => ['required', Rule::unique('payment_methods', 'name'), Rule::unique('payment_methods', 'id')]
        ]);
        $action->execute($this->state);

        flasher("success", "Le moyen de paiement a bien été créé.");

        return redirect()->route('paymentMethods.index');
    }

    public function render()
    {
        return view('livewire.payment-methods.create-payment-method-form');
    }
}
