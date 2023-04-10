<?php

namespace App\Http\Livewire\Departments;

use Livewire\Component;
use App\Models\Department;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Actions\Department\UpdateDepartmentAction;
use Filament\Notifications\Notification;

class EditDepartmentForm extends Component implements HasForms
{
    use InteractsWithForms;
    public $department;

    public $state = [
        'name' => null,
    ];

    public function mount(Department $department)
    {
        $this->department = $department;

        $this->form->fill([
            'state.name' => $department->name,
        ]);
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

    public function saveDepartment(UpdateDepartmentAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->department, $this->state);

        flasher("success", "Le département a bien été modifié.");

        return redirect()->route('departments.index');
    }

    public function render()
    {
        return view('livewire.departments.edit-department-form');
    }
}
