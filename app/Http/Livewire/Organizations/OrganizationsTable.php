<?php

namespace App\Http\Livewire\Organizations;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Actions\Department\DeleteDepartmentAction;
use App\Actions\Organization\DeleteOrganizationAction;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class OrganizationsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $organizationIdBeingDeleted;
    public $confirmingOrganizationDeletion = false;

    public $OrganizationIdBeingRestored;
    public $confirmingOrganizationRestoration = false;

    public function columns(): array
    {
        return [
            Column::make('Date de création', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Nom', 'name')->sortable()->searchable(),
            Column::make("Nbr d'employés")->format(fn ($value, $column, Organization $row) => $row->users_count),
            Column::make('Actions')->format(function ($value, $column, Organization $row) {
                return view('livewire.organizations.table-actions', ['organization' => $row]);
            }),
        ];
    }

    public function confirmOrganizationDeletion($organizationId)
    {
        $this->organizationIdBeingDeleted = $organizationId;
        $this->confirmingOrganizationDeletion = true;
    }

    public function deleteOrganization(DeleteOrganizationAction $action)
    {
        $menu = Organization::find($this->organizationIdBeingDeleted);

        $action->execute($menu);

        $this->confirmingOrganizationtDeletion = false;
        $this->organizationIdBeingDeleted = null;

        session()->flash('success', "La société a été supprimé avec succès !");

        return redirect()->route('organizations.index');
    }

    public function modalsView(): string
    {
        return 'livewire.organizations.modals';
    }

    public function query(): Builder
    {
        return Organization::query()->withCount('users');
    }
}
