<?php

namespace App\Http\Livewire\UserTypes;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Actions\UserType\CreateUserTypeAction;
use Filament\Forms\Concerns\InteractsWithForms;

class CreateUserTypeForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $state = [
        'name' => null,
        'auto_identifier' => false
    ];

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('state.name')
                ->label('Nom')
                ->required()
                ->rules('required', 'max:255')
        ];
    }

    public function saveUserType(CreateUserTypeAction $createUserTypeAction)
    {
        $this->validate([
            'state.name' => ['required', 'string', Rule::unique('user_types', 'name')],
            'state.auto_identifier' => ['nullable']
        ]);

        $createUserTypeAction->execute($this->state);

        Notification::make()
            ->title('Le type d\'utilisateur a été créé avec succès')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->send();

        return redirect()->route('userTypes.index');
    }

    public function render()
    {
        return view('livewire.user-types.create-user-type-form');
    }
}
