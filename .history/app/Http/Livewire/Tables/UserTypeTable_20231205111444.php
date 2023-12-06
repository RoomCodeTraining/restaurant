<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class UserTypeTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\UserType::query())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->sortable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM'),
                TextColumn::make('users_count')->label('NBR D\'ÉMPLOYÉS'),

            ])->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (UserType $record): string => route('organizations.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Organization $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::make()->title('Departement supprimé avec succès !')->danger()->send();

                            return redirect()->route('departments.index');
                        })
                        ->hidden(fn (UserType $record) => $record->users->count() > 0)
                        ->action(fn (UserType $record) => $record->delete()),

                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.user-type-table');
    }
}
