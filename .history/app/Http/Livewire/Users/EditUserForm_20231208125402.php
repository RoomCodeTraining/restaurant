<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\UserType;
use App\Models\Department;
use App\Models\Organization;
use Livewire\WithFileUploads;
use App\Models\EmployeeStatus;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Actions\User\UpdateUserAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditUserForm extends Component implements HasForms
{
    use AuthorizesRequests;
    use WithFileUploads, InteractsWithForms;

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
        'is_entitled_breakfast' => false,
        'can_order_two_dishes' => false,
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->state = $user->toArray();
        $this->role = $this->user->roles->first()->id ?? \App\Models\Role::USER;
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('state.identifier')
                        ->label('Matricule/Identifiant')
                        ->autofocus()
                        ->placeholder('TKOL8'),
                    TextInput::make('state.first_name')
                        ->label('Nom')
                        ->required()
                        ->autofocus(),
                    TextInput::make('state.last_name')
                        ->label('Prénoms')
                        ->required()
                        ->autofocus(),
                    TextInput::make('state.email')
                        ->label('E-mail')
                        ->required()
                        ->autofocus(),
                    TextInput::make('state.contact')
                        ->label('Contact')
                        ->required()
                        ->autofocus(),
                    Select::make('state.role')
                        ->label('Rôle')
                        ->options(Role::pluck('name', "id"))
                        ->autofocus(),
                    Select::make('state.employee_status_id')
                        ->label('Catégorie professionnelle')
                        ->required()
                        ->options(EmployeeStatus::pluck('name', "id"))
                        ->autofocus(),
                    Select::make('state.organization_id')
                        ->label('Société')
                        ->options(Organization::pluck('name', "id"))
                        ->required()
                        ->autofocus(),
                    Select::make('state.department_id')
                        ->label('Departement')
                        ->options(Department::pluck('name', "id"))
                        ->required()
                        ->autofocus(),
                    Select::make('state.user_type_id')
                        ->label('Type de collaborateur')
                        ->options(UserType::pluck('name', "id"))
                        ->required()
                        ->autofocus(),
                    Toggle::make('state.is_entitled_breakfast')
                        ->label('Le collaborateur a droit au petit déjeuner ?')
                        ->onColor('success')
                        ->offColor('danger'),
                ])->columns(2)
        ];
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
