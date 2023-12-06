<?php

namespace App\Http\Livewire\UserTypes;

use Livewire\Component;
use App\Models\UserType;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Actions\UserType\UpdateUserTypeAction;
use Filament\Forms\Concerns\InteractsWithForms;

class EditUserTypeForm extends Component implements HasForms
{
    use InteractsWithForms;
    public UserType $userType;
    public ?array $datInteractsWithFormsa = [];

    public $state = [
        'name' => null,
        'auto_identifier' => null,
    ];

    public function mount(): void
    {

        $this->form->fill([
            'state.name' => $this->userType->name,
            //'state.auto_identifier' => $this->userType->auto_identifier,
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
                    ])
                // ...
            ])->statePath('state');
    }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('state.name')
    //             ->label('Nom')
    //             ->required()
    //             ->rules('required', 'max:255')
    //     ];
    // }

    public function saveUserType(UpdateUserTypeAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->userType, $this->state);

        Notification::make()
            ->title('Le type d\'utilisateur a été modifié avec succès')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->send();

        return redirect()->route('userTypes.index');
    }

    public function render()
    {
        return view('livewire.user-types.edit-user-type-form');
    }
}
