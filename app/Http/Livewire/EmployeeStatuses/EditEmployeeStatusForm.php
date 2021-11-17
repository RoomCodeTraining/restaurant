<?php

namespace App\Http\Livewire\EmployeeStatuses;

use App\Actions\EmployeeStatus\UpdateEmployeeStatusAction;
use App\Models\EmployeeStatus;
use Livewire\Component;

class EditEmployeeStatusForm extends Component
{
    public $employeeStatus;

    public $state = [
        'name' => null,
    ];

    public function mount(EmployeeStatus $employeeStatus)
    {
        $this->employeeStatus = $employeeStatus;
        $this->state = $employeeStatus->toArray();
    }

    public function saveEmployeeStatus(UpdateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->employeeStatus, $this->state);

        session()->flash('success', 'La catégorie a été modifié avec succès !');

        return redirect()->route('employeeStatuses.index');
    }

    public function render()
    {
        return view('livewire.employee-statuses.edit-employee-status-form');
    }
}
