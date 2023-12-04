<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;

class OrganizationTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Organization::query()->withCount('users'))
            ->columns([
                TextColumn::make('created_at')->label('Date de création')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('Nom'),
                TextColumn::make('users_count')->label('NBR D\'EMPLOYES'),
            ])->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (Department $record): string => route('departments.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Department $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::success('Departement supprimé avec succès');
                            return redirect()->route('departments.index');
                        })
                        ->hidden(fn (Department $record) => $record->users_count > 0)
                        ->action(fn (Department $record) => $record->delete()),

                ]),
            ]);;
    }

    public function render()
    {
        return view('livewire.tables.organization-table');
    }
}
