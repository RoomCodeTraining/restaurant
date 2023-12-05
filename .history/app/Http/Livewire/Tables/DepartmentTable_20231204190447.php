<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\Department;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class DepartmentTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table($table)
    {
        return $table
            ->query(\App\Models\Department::query())
            ->columns([
                TextColumn::make('created_at')->label('Date de création')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('Nom'),
                TextColumn::make('NBR D\'EMPLOYES')
                    ->label('Description')->format(fn ($value, $column, Department $row) => $row->users_count),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.department-table');
    }
}
