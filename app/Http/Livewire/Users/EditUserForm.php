<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Actions\User\UpdateUserAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditUserForm extends Component
{
    use AuthorizesRequests;

    public $user;

    public $role;

    public $state = [
        'identifier' => null,
        'username' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'contact' => null,
        'employee_status_id' => null,
        'organization_id' => null,
        'department_id' => null,
        'is_external' => false,
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->state = $user->toArray();
        $this->role = $this->user->roles->first()->id ?? \App\Models\Role::USER;
    }

    public function saveUser(UpdateUserAction $updateUserAction)
    {
        $this->authorize('create', User::class);

        $this->validate([
            'state.first_name' => ['required', 'string', 'max:255'],
            'state.last_name' => ['required', 'string', 'max:255'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel($this->user)],
            'state.identifier' => ['required', 'max:255', Rule::unique('users', 'identifier')->ignoreModel($this->user)],
            'state.username' => ['required', 'max:255', Rule::unique('users', 'username')->ignoreModel($this->user)],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.department_id' => ['required', Rule::exists('departments', 'id')],
            'state.employee_status_id' => ['required', 'exists:employee_statuses,id', Rule::exists('employee_statuses', 'id')],
            'state.organization_id' => ['required', Rule::exists('organizations', 'id')],
            'role' => ['required', Rule::exists('roles', 'id')],
        ]);

        $updateUserAction->execute($this->user, array_merge($this->state, ['roles' => [$this->role]]));

        session()->flash('success', "L'utilisateur a été créé avec succès!");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit-user-form', [
            'departments' => \App\Models\Department::pluck('name', 'id'),
            'employeeStatuses' => \App\Models\EmployeeStatus::pluck('name', 'id'),
            'organizations' => \App\Models\Organization::pluck('name', 'id'),
            'roles' => \App\Models\Role::pluck('name', 'id'),
        ]);
    }
}
