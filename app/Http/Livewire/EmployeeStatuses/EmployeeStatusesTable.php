<?php

namespace App\Http\Livewire\EmployeeStatuses;

use App\Models\EmployeeStatus;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class EmployeeStatusesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

    public $modelIdBeingDeleted;
    public $confirmingModelDeletion = false;

    public $modelIdBeingRestored;
    public $confirmingModelRestoration = false;

    public function query(): Builder
    {
        return EmployeeStatus::query()->withCount('users');
    }

    public function columns(): array
    {
        return [
            Column::make('Date de création', 'created_at')->sortable(),
            Column::make('Nom', 'name')->sortable()->searchable(),
            Column::make("Nbr d'employés")->format(fn ($value, $column, $row) => $row->users_count),
            Column::make('Actions')->format(function ($value, $column, $row) {
                return view('livewire.employee-statuses.table-actions', ['model' => $row]);
            }),
        ];
    }

    public function confirmModelDeletion($modelId)
    {
        $this->modelIdBeingDeleted = $modelId;
        $this->confirmingModelDeletion = true;
    }

    public function deleteModel()
    {
        $model = EmployeeStatus::find($this->modelIdBeingDeleted);

        $model->delete();

        $this->confirmingModelDeletion = false;
        $this->modelIdBeingDeleted = null;

        session()->flash('success', "La catégorie professionnelle a été supprimé avec succès !");

        return redirect()->route('employeeStatuses.index');
    }

    public function modalsView(): string
    {
        return 'livewire.employee-statuses.modals';
    }
}
