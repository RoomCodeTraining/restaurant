<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use App\Models\Department;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class DepartmentTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table($table)
    {
        return $table
            ->query(\App\Models\Department::query()->withCount('users')->latest())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM')->searchable(),
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
                            Notification::make()->title('Sociéte supprimé supprimé avec succès !')->danger()->send();
                            return redirect()->route('departments.index');
                        })
                        ->hidden(fn (Department $record) => $record->users->count() > 0)
                        ->hidden(!Auth::user()->isAdmin())
                        ->action(fn (Department $record) => $record->delete()),

                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.department-table');
    }
}
