<?php

namespace App\Http\Livewire\Users;

use App\Actions\User\CreateUserAction;
use App\Models\Department;
use App\Models\EmployeeStatus;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUserForm extends Component implements HasForms
{
    use AuthorizesRequests, WithFileUploads, InteractsWithForms;


    public $role = null;

    public $profile_photo = null;

    public $generateIdentifierFor = [];
    public $user_type_id = null;

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
        "is_entitled_breakfast" => false,
        'can_order_two_dishes' => false,
    ];

    public function mount()
    {
        $this->role = Role::USER;
        $this->state['organization_id'] = Organization::firstWhere('name', 'Ciprel')->id;
        $this->generateIdentifierFor = UserType::where('auto_identifier', true)->pluck('id')->toArray();
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
                        ->numeric()
                        ->autofocus(),
                    Select::make('role')
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
                        ->label('Département')
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


    public function updated($field, $value)
    {
        if ($field === "state.user_type_id") {
            if (in_array($value, $this->generateIdentifierFor)) {
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
            'state.employee_status_id' => ['required', Rule::exists('employee_statuses', 'id')],
            'state.user_type_id' => ['required', Rule::exists('user_types', 'id')],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
            'role' => ['required', Rule::exists('roles', 'id')],
            'state.is_entitled_breakfast' => ['required', 'boolean'],
        ]);


        $user = $createUserAction->execute(array_merge($this->state, ['roles' => [$this->role]]));

        if ($this->profile_photo) {
            $user->updateProfilePhoto($this->profile_photo);
        }

        Notification::make()->title('Le collaborateur a été créé avec succès !')->success()->send();

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create-user-form', [
            'departments' => \App\Models\Department::pluck('name', 'id'),
            'employeeStatuses' => \App\Models\EmployeeStatus::pluck('name', 'id'),
            'organizations' => \App\Models\Organization::pluck('name', 'id'),
            'roles' => \App\Models\Role::pluck('name', 'id'),
            'userTypes' => \App\Models\UserType::pluck('name', 'id'),
        ]);
    }
}
