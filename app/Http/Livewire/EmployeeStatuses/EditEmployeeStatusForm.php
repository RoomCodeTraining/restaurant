<?php

namespace App\Http\Livewire\EmployeeStatuses;

use App\Actions\EmployeeStatus\UpdateEmployeeStatusAction;
use App\Models\EmployeeStatus;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

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
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->rules('required', 'max:255'),
            ])
            ->statePath('state');
    }

    public function saveEmployeeStatus(UpdateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string', 'unique:employee_statuses,name,' . $this->employeeStatus->id . ',id'],
        ]);

        $action->execute($this->employeeStatus, $this->state);

        flasher('success', 'Le statut a bien été modifié avec succès.');

        return redirect()->route('employeeStatuses.index');
    }

    public function render()
    {
        return view('livewire.employee-statuses.edit-employee-status-form');
    }
}