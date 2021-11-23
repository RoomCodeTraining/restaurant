<?php

namespace App\Http\Livewire\Departments;

use App\Actions\Department\UpdateDepartmentAction;
use App\Models\Department;
use Livewire\Component;

class EditDepartmentForm extends Component
{
    public $department;

    public $state = [
        'name' => null,
    ];

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->state = $department->toArray();
    }

    public function saveDepartment(UpdateDepartmentAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string'],
        ]);

        $action->execute($this->department, $this->state);

        session()->flash('success', 'Le departement a été modifié avec succès !');

        return redirect()->route('departments.index');
    }

    public function render()
    {
        return view('livewire.departments.edit-department-form');
    }
}
