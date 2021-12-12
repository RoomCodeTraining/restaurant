<?php

namespace App\Http\Livewire\Users;

use App\Actions\User\UpdateUserAction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUserForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $user;

    public $role;

    public $profile_photo = null;

    public $confirmingUpdate = false;

    public $state = [
        'identifier' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'contact' => null,
        'employee_status_id' => null,
        'organization_id' => null,
        'department_id' => null,
        'user_type_id' => null,
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->state = $user->toArray();
        $this->role = $this->user->roles->first()->id ?? \App\Models\Role::USER;
    }

    public function confirmUpdate()
    {
        $this->confirmingUpdate = true;
    }

    public function saveUser(UpdateUserAction $updateUserAction)
    {
        $this->authorize('create', User::class);

        $this->validate([
            'state.identifier' => ['required', 'max:5', Rule::unique('users', 'identifier')->ignoreModel($this->user)],
            'state.first_name' => ['required', 'string', 'max:50'],
            'state.last_name' => ['required', 'string', 'max:50'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel($this->user)],
            'state.contact' => ['required', 'string', 'min:10', 'max:20'],
            'state.department_id' => ['nullable', Rule::exists('departments', 'id')],
            'state.employee_status_id' => ['required', 'exists:employee_statuses,id', Rule::exists('employee_statuses', 'id')],
            'state.user_type_id' => ['required'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
            'role' => ['required', Rule::exists('roles', 'id')],
        ]);

        $user = $updateUserAction->execute($this->user, array_merge($this->state, ['roles' => [$this->role]]));

        if ($this->profile_photo) {
            $user->updateProfilePhoto($this->profile_photo);
        }

        session()->flash('success', "L'utilisateur a modifié avec succès!");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit-user-form', [
            'departments' => \App\Models\Department::pluck('name', 'id'),
            'employeeStatuses' => \App\Models\EmployeeStatus::pluck('name', 'id'),
            'organizations' => \App\Models\Organization::pluck('name', 'id'),
            'roles' => \App\Models\Role::pluck('name', 'id'),
            'userTypes' => \App\Models\UserType::pluck('name', 'id'),
        ]);
    }
}
