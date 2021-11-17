<?php

namespace App\Http\Livewire\UserTypes;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UserTypesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $userTypeIdBeingDeleted;
    public $confirmingUserTypeDeletion = false;

    public $userTypeIdBeingRestored;
    public $confirmingUserTypeRestoration = false;

    public function query(): Builder
    {
        return UserType::query()->withCount('users');
    }

    public function columns(): array
    {
        return [
            Column::make('Date de création', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Nom', 'name')->sortable()->searchable(),
            Column::make("Nbr d'employés")->format(fn ($value, $column, UserType $row) => $row->users_count),
            Column::make('Actions')->format(function ($value, $column, UserType $row) {
                return view('livewire.user-types.table-actions', ['userType' => $row]);
            }),
        ];
    }

    public function confirmUserTypeDeletion($userTypeId)
    {
        $this->userTypeIdBeingDeleted = $userTypeId;
        $this->confirmingUserTypeDeletion = true;
    }

    public function deleteUserType()
    {
        $userType = UserType::find($this->userTypeIdBeingDeleted);

        $userType->delete();

        $this->confirmingUserTypeDeletion = false;
        $this->userTypeIdBeingDeleted = null;

        session()->flash('success', "Le type d'utilisateur a été supprimé avec succès !");

        return redirect()->route('userTypes.index');
    }

    public function modalsView(): string
    {
        return 'livewire.user-types.modals';
    }
}
