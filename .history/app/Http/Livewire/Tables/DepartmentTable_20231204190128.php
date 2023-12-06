<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

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
                TextColumn::make('NBR D\'EMPLOYES4)->label('Description'),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.department-table');
    }
}