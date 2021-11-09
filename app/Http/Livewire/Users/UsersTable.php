<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Events\UserLocked;
use App\Events\UserUnlocked;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UsersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";
    public array $filterNames = [
        'type' => 'Profil',
        'active' => 'Etat du compte',
    ];

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $userIdBeingLocked;
    public $confirmingUserLocking = false;

    public $userIdBeingUnlocked;
    public $confirmingUserUnlocking = false;

    public function columns(): array
    {
        return [
            Column::make('Matricule', 'identifier')->sortable()->searchable(),
            Column::make('Nom & Prénoms', 'full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Email', 'email')->sortable()->searchable(),
            Column::make('Contact', 'contact')->sortable()->searchable(),
            Column::make('Profil')->format(function ($val, $col, User $user) {
                return optional($user->roles()->first())->name;
            }),
            Column::make('Etat du compte')->format(function ($val, $col, User $user) {
                return view('livewire.users.status', ['user' => $user]);
            }),
            Column::make('Actions')->format(function ($value, $column, User $row) {
                return view('livewire.users.table-actions', ['user' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        // dd(Role::pluck('name', 'name')->toArray());
        return [
            'type' => Filter::make('Profil')
                ->select(array_merge(['' => 'Tous les types'], Role::pluck('name', 'name')->toArray())),
            'active' => Filter::make('Etat du compte')
                ->select(array_merge(['' => 'Tous les états'], [
                    'yes' => 'Actif',
                    'no' => 'Inactif',
                ])),
        ];
    }

    public function confirmUserLocking($userId)
    {
        $this->userIdBeingLocked = $userId;
        $this->confirmingUserLocking = true;
    }

    public function lockUser()
    {
        $user = User::find($this->userIdBeingLocked);

        $user->update(['is_active' => false]);

        UserLocked::dispatch($user);

        $this->confirmingUserLocking = false;
        $this->userIdBeingLocked = null;

        session()->flash('success', "L'utilisateur a été désactivé avec succès !");

        return redirect()->route('users.index');
    }

    public function confirmUserUnlocking($userId)
    {
        $this->userIdBeingUnlocked = $userId;
        $this->confirmingUserUnlocking = true;
    }

    public function unlockUser()
    {
        $user = User::find($this->userIdBeingUnlocked);

        $user->update(['is_active' => true]);

        UserUnlocked::dispatch($user);

        $this->confirmingUserUnlocking = false;
        $this->userIdBeingUnlocked = null;

        session()->flash('success', "L'utilisateur a été activé avec succès !");

        return redirect()->route('users.index');
    }

    public function modalsView(): string
    {
        return 'livewire.users.modals';
    }

    public function query(): Builder
    {
        return User::query()
        ->when($this->getFilter('type'), function ($query, $type) {
            return $query->whereHas('roles', function ($query) use ($type) {
                // dd($type);
                return $query->where('name', $type);
            });
        })
        ->when($this->getFilter('active'), fn ($query, $active) => $query->where('is_active', $active === 'yes'));
    }
}
