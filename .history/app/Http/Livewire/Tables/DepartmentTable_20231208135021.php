<?php

namespace App\Http\Livewire\Tables;

use App\Models\Department;
use App\Support\ActivityHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
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
            ->query(\App\Models\Department::query()->withCount('users')->latest())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM')->searchable(),
                TextColumn::make('users_count')->label('NBR D\'EMPLOYES'),
            ])->actions([
                Action::make('Editer')
                    ->url(fn (Department $record): string => route('departments.edit', $record))
                    ->label('')
                    ->tooltip('Editer le département')
                    ->color('info')
                    ->icon('heroicon-o-pencil-square'),
                Action::make('Supprimer')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->label('')
                    ->tooltip('Supprimer le département')
                    ->color('danger')
                    ->modalHeading('Supprimer le département')
                    ->modalDescription('Êtes-vous sûr de vouloir supprimer ce département ?')
                    ->hidden(fn (Department $record) => $record->users->count() > 0)
                    ->action(function (Department $record) {
                        $record->delete();

                        ActivityHelper::createActivity($record, 'Suppression du departement ' . $record->name, 'Suppression du departement');
                        Notification::make()->title('Suppression du département')->success()->body('Le département a été supprimé avec succès !')->send($record->user);
                    }),

            ]);
    }
    public function render()
    {
        return view('livewire.tables.department-table');
    }
}
