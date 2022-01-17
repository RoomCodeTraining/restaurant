<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use App\Events\UserLocked;
use App\Exports\UserExport;
use App\Events\UserUnlocked;
use Maatwebsite\Excel\Facades\Excel;
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

     public array $bulkActions = [
        'exportToUser' => 'Export au format Excel',
    ];

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $userIdBeingLocked;
    public $confirmingUserLocking = false;

    public $userIdBeingUnlocked;
    public $confirmingUserUnlocking = false;

    public function query(): Builder
    {
        return User::query()
            ->with('role')
            ->when($this->getFilter('type'), fn ($query, $type) => $query->whereRelation('roles', 'name', $type))
            ->when($this->getFilter('active'), fn ($query, $active) => $query->where('is_active', $active === 'yes'));
    }

    public function columns(): array
    {
        return [
            Column::make('Matricule', 'identifier')->searchable(),
            Column::make('Nom & Prénoms', 'full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Email', 'email')->searchable(),
            Column::make('Contact', 'contact')->searchable(),
            Column::make('Profil')->format(fn ($val, $col, User $user) => $user->role->name),
            Column::make('Etat du compte')->format(fn ($val, $col, User $user) => view('livewire.users.status', ['user' => $user])),
            Column::make('Actions')->format(fn ($val, $col, User $user) => view('livewire.users.table-actions', ['user' => $user])),
        ];
    }

    public function filters(): array
    {
        return [
            'type' => Filter::make('Profil')
                ->select(array_merge(['' => 'Tous les types'], Role::pluck('name', 'name')->toArray())),
            'active' => Filter::make('Etat du compte')
                ->select(array_merge(['' => 'Tous les états'], [ 'yes' => 'Actif', 'no' => 'Inactif', ])),
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


    public function exportToUser()
    {
        return Excel::download(new UserExport(), 'utilisateurs.xlsx');
    }


    public function modalsView(): string
    {
        return 'livewire.users.modals';
    }
}
