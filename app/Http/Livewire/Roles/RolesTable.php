<?php

namespace App\Http\Livewire\Roles;

use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class RolesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $roleIdBeingDeleted;
    public $confirmingRoleDeletion = false;

    public array $bulkActions = [
        'exportToRolesAndPermissions' => 'Export au format Excel',
    ];

    public function query(): Builder
    {
        return Role::query()->withCount('users');
    }

    public function columns(): array
    {
        return [
            Column::make('Rôle', 'name')->sortable()->searchable(),
            Column::make('Description'),
            Column::make("Nbre d'utilisateurs associés")->format(fn ($value, $column, Role $row) => $row->users_count),
            Column::make('Actions')->format(fn ($value, $column, Role $row) => view('livewire.roles.table-actions', ['role' => $row])),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.roles.modals';
    }

    public function confirmRoleDeletion($RoleId)
    {
        $this->roleIdBeingDeleted = $RoleId;
        $this->confirmingRoleDeletion = true;
    }


    public function exportToRolesAndPermissions(){
        return Excel::download(new \App\Exports\RolesAndPermissionsExport, 'roles-and-permissions.xlsx');
    }

    public function deleteRole()
    {
        Role::destroy($this->roleIdBeingDeleted);

        $this->confirmingRoleDeletion = false;

        session()->flash('success', "Le rôle a été supprimé avec succès !");

        return redirect()->route('backend.roles.index');
    }
}
