<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\Department;
use App\Models\Organization;
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
                TextColumn::make('id.description')->label('Description')->formatStateUsing(function ($record) {

                    return $record->description ? $record->description : 'Aucune description';
                }),
                TextColumn::make('users_count')->label('NBR D\'EMPLOYES'),
            ])->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (Organization $record): string => route('organizations.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Organization $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::make()->title('Sociéte supprimé supprimé avec succès !')->danger()->send();
                            return redirect()->route('organizations.index');
                        })
                        ->hidden(fn (Organization $record) => $record->users->count() > 0)
                        ->action(fn (Organization $record) => $record->delete()),

                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.organization-table');
    }
}
