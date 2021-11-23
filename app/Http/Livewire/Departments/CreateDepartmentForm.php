<?php

namespace App\Http\Livewire\Departments;

use App\Actions\Department\CreateDepartmentAction;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateDepartmentForm extends Component
{
    public $state = ['name' => null];
    public function saveDepartment(CreateDepartmentAction $action)
    {
        $this->validate([
            'state.name' => ['required', 'string',  Rule::unique('users', 'identifier')],
        ]);

        $action->execute($this->state);
        session()->flash('success', 'Le departement a été crée avec succès !');

        return redirect()->route('departments.index');
    }
    public function render()
    {
        return view('livewire.departments.create-department-form');
    }
}
