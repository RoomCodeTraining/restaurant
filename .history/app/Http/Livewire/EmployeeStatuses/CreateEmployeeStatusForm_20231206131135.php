<?php

namespace App\Http\Livewire\EmployeeStatuses;

use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\EmployeeStatus\CreateEmployeeStatusAction;

class CreateEmployeeStatusForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $state = [
        'name' => null,
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

    // protected function getFormSchema(): array
    // {
    //     return [
    //         TextInput::make('state.name')
    //             ->label('Nom')
    //             ->required()
    //             ->rules('required', 'max:255', 'unique:employee_statuses,name'),
    //     ];
    // }

    public function saveEmployeeStatus(CreateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string', Rule::unique('employee_statuses', 'name')],
        ]);

        $action->execute($this->state);

        flasher("success", "Le statut a bien été créé avec suuccès.");
        return redirect()->route('employeeStatuses.index');
    }

    public function render()
    {
        return view('livewire.employee-statuses.create-employee-status-form');
    }
}
