<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\Department;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class DepartmentTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table($table)
    {
        return $table
            ->query(\App\Models\Department::query()->withCount('users'))
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM'),
                TextColumn::make('users_count')->label('NBR D\'EMPLOYES'),
            ])->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (Department $record): string => route('departments.edit', $record))
                        ->icon('heroicon-o-pencil'),

                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.department-table');
    }
}
