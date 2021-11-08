<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Actions\User\DeleteUserAction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UsersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $userIdBeingDeleted;
    public $confirmingUserDeletion = false;

    public $userIdBeingRestored;
    public $confirmingUserRestoration = false;

    public function columns(): array
    {
        return [
            Column::make('Code', 'identifier')->sortable()->searchable(),
            Column::make('Username', 'username')->sortable()->searchable(),
            Column::make('Nom & Prénoms', 'full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Email', 'email')->sortable()->searchable(),
            Column::make('Categorie', 'employeeStatus.name')->sortable()->searchable(),
            Column::make('Actions')->format(function ($value, $column, User $row) {
                return view('livewire.users.table-actions', ['user' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        return [
            //DepartmentFilter::$id => DepartmentFilter::make(),
        ];
    }

    public function confirmUserDeletion($userId)
    {
        $this->userIdBeingDeleted = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function confirmUserRestoration($userId)
    {
        $this->userIdBeingRestored = $userId;
        $this->confirmingUserRestoration = true;
    }

    public function deleteUser(DeleteUserAction $action)
    {
        $user = User::find($this->userIdBeingDeleted);
        $action->execute($user);
        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;

        session()->flash('success', "L'utilisateur a été supprimé avec succès !");

        return redirect()->route('users.index');
    }

    public function modalsView(): string
    {
        return 'livewire.users.modals';
    }

    public function query(): Builder
    {
        return User::query();
    }
}
