<?php

namespace App\Http\Livewire\EmployeeStatuses;

use App\Actions\EmployeeStatus\CreateEmployeeStatusAction;
use Livewire\Component;

class CreateEmployeeStatusForm extends Component
{
    public $state = [
        'name' => null,
    ];

    public function saveEmployeeStatus(CreateEmployeeStatusAction $action)
    {
        $this->validate([
            'state.name' => ['required'],
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
