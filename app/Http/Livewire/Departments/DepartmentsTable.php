<?php

namespace App\Http\Livewire\Departments;

use App\Actions\Department\DeleteDepartmentAction;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DepartmentsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $departmentIdBeingDeleted;
    public $confirmingDepartmentDeletion = false;

    public $departmentIdBeingRestored;
    public $confirmingDepartmentRestoration = false;

    public function columns(): array
    {
        return [
            Column::make('Date de création', 'created_at')->format(fn ($row) => $row->format('d/m/Y'))->sortable(),
            Column::make('Nom', 'name')->sortable()->searchable(),
            Column::make("Nbr d'employés")->format(fn ($value, $column, Department $row) => $row->users_count),
            Column::make('Actions')->format(function ($value, $column, Department $row) {
                return view('livewire.departments.table-actions', ['department' => $row]);
            }),
        ];
    }

    public function confirmDepartmentDeletion($departmentId)
    {
        $this->departmentIdBeingDeleted = $departmentId;
        $this->confirmingDepartmentDeletion = true;
    }

    public function deleteDepartment(DeleteDepartmentAction $action)
    {
        $department = Department::find($this->departmentIdBeingDeleted);

        $action->execute($department);

        $this->confirmingDepartmentDeletion = false;
        $this->departmentIdBeingDeleted = null;

        session()->flash('success', "Le department a été supprimé avec succès !");

        return redirect()->route('departments.index');
    }

    public function modalsView(): string
    {
        return 'livewire.departments.modals';
    }

    public function query(): Builder
    {
        return Department::query()->withCount('users');
    }
}
