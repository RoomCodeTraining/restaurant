<?php

namespace App\Http\Livewire\EmployeeStatuses;

use Livewire\Component;
use Filament\Forms\Form;
use App\Models\EmployeeStatus;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\EmployeeStatus\UpdateEmployeeStatusAction;

class EditEmployeeStatusForm extends Component implements HasForms
{
    use InteractsWithForms;
    public EmployeeStatus $employeeStatus;

    public $state = [
        'name' => null,
    ];

    public function mount(): void
    {
        $this->form->fill([
            'name' => $this->employeeStatus->name,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Modification des informations à la catégorie professionnelle')
                    ->description('Veuillez saisir des noms de catégorie professionnelle corrects pour une meilleure affiliation')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->rules('required', 'max:255'),
                        // Textarea::make('description')
                        //     ->label('Description')
                        //     ->rules('required', 'max:255'),
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
        ];
    }

    public function saveEmployeeStatus(UpdateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->employeeStatus, $this->state);

        flasher("success", "Le statut a bien été modifié avec succès.");
        return redirect()->route('employeeStatuses.index');
    }

    public function render()
    {
        return view('livewire.employee-statuses.edit-employee-status-form');
    }
}
