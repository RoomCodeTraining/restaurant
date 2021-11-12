<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Enums\UserTypes;
use Illuminate\Support\Str;
use App\Models\Organization;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Actions\User\CreateUserAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateUserForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $role = null;

    public $profile_photo = null;

    public $state = [
        // 'identifier' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'contact' => null,
        'employee_status_id' => null,
        'organization_id' => null,
        'department_id' => null,
        'user_type' => null,
    ];

    public function mount()
    {
        $this->role = Role::USER;
        $this->state['organization_id'] = Organization::firstWhere('name', '=', 'Ciprel')->id;
    }

    public function updated($field, $value)
    {
        if ($field === "state.user_type") {
            if (UserTypes::NON_CIPREL_AGENT == $value) {
                $this->state['identifier'] = Str::upper(Str::random(5));
            } else {
                $this->state['identifier'] = $this->state['identifier'] ?? null;
            }
        }
    }

    public function saveUser(CreateUserAction $createUserAction)
    {
        $this->authorize('create', User::class);

        $this->validate([
            'state.identifier' => ['required', 'min:5', 'max:5', Rule::unique('users', 'identifier')],
            'state.first_name' => ['required', 'string', 'max:50'],
            'state.last_name' => ['required', 'string', 'max:50'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')],
            'state.contact' => ['required', 'string', 'min:10', 'max:20'],
            'state.department_id' => ['required', Rule::exists('departments', 'id')],
            'state.organization_id' => ['required', Rule::exists('organizations', 'id')],
            'state.employee_status_id' => ['required', 'exists:employee_statuses,id', Rule::exists('employee_statuses', 'id')],
            'state.user_type' => ['required'],
            'profile_photo' => ['required', 'image', 'max:1024'],
            'role' => ['required', Rule::exists('roles', 'id')],
        ]);

        $user = $createUserAction->execute(array_merge($this->state, ['roles' => [$this->role]]));

        $user->updateProfilePhoto($this->profile_photo);

        session()->flash('success', "L'utilisateur a été créé avec succès!");

        return redirect()->route('users.index');
    }

    public function messages()
    {
        return [
            'required' => 'Ce champ est obligatoire',
            'max' => 'Ce champ ne doit pas dépasser :max caractères',
            'min' => 'Ce champ doit contenir au moins :min caractères',
            'email' => 'Ce champ doit être un email valide',
            'unique' => 'Ce champ doit être unique',
            'exists' => 'Ce champ doit être un élément existant',
            'image' => 'Ce champ doit être une image',
        ];
    }

    public function render()
    {
        return view('livewire.users.create-user-form', [
            'departments' => \App\Models\Department::pluck('name', 'id'),
            'employeeStatuses' => \App\Models\EmployeeStatus::pluck('name', 'id'),
            'organizations' => \App\Models\Organization::pluck('name', 'id'),
            'roles' => \App\Models\Role::pluck('name', 'id'),
            'userTypes' => UserTypes::getUserTypes(),
        ]);
    }
}
