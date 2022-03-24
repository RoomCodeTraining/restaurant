<?php

namespace App\Http\Livewire\EmployeeStatuses;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Actions\EmployeeStatus\CreateEmployeeStatusAction;

class CreateEmployeeStatusForm extends Component
{
    public $state = [
        'name' => null,
    ];

    public function saveEmployeeStatus(CreateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string', Rule::unique('employee_statuses', 'name')],
        ]);

        $action->execute($this->state);

        session()->flash('success', 'La catégorie a été crée avec succès !');

        return redirect()->route('employeeStatuses.index');
    }

    public function render()
    {
        return view('livewire.employee-statuses.create-employee-status-form');
    }
}
