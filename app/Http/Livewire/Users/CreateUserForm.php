<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;
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
        'identifier' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'contact' => null,
        'employee_status_id' => null,
        'organization_id' => null,
        'department_id' => null,
        'is_external' => null,
    ];

    public function saveUser(CreateUserAction $createUserAction)
    {
        $this->authorize('create', User::class);

        $this->validate([
            'state.identifier' => ['required', 'max:5', Rule::unique('users', 'identifier')],
            'state.first_name' => ['required', 'string', 'max:50'],
            'state.last_name' => ['required', 'string', 'max:50'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')],
            'state.contact' => ['required', 'string', 'min:10', 'max:20'],
            'state.department_id' => ['required', Rule::exists('departments', 'id')],
            'state.employee_status_id' => ['required', 'exists:employee_statuses,id', Rule::exists('employee_statuses', 'id')],
            'state.is_external' => ['required'],
            'profile_photo' => ['required', 'image', 'max:1024'],
            'role' => ['required', Rule::exists('roles', 'id')],
        ]);

        $this->state['is_external'] = $this->state['is_external'] === 'yes' ? true : false;

        $user = $createUserAction->execute(array_merge($this->state, ['roles' => [$this->role]]));

        $user->updateProfilePhoto($this->profile_photo);

        session()->flash('banner', "L'utilisateur a été créé avec succès!");

        return redirect()->route('users.index');
    }

    public function messages()
    {
        return [
            'required' => 'Cette valeur est requise',
            'string' => 'Cette valeur doit etre une chaine de caractere',
            'email' => 'Cette valeur doit etre une adresse email',
            'max' => 'Cette valeur est trop grande',
            'min' => 'Cette valeur est trop petite',
        ];
    }

    public function render()
    {
        return view('livewire.users.create-user-form', [
            'departments' => \App\Models\Department::pluck('name', 'id'),
            'employeeStatuses' => \App\Models\EmployeeStatus::pluck('name', 'id'),
            'organizations' => \App\Models\Organization::pluck('name', 'id'),
            'roles' => \App\Models\Role::pluck('name', 'id'),
        ]);
    }
}
