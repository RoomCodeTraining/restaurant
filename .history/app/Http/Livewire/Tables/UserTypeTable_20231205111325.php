<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

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
                        ->url(fn (Organization $record): string => route('organizations.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (Organization $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::success('Departement supprimé avec succès');
                            return redirect()->route('departments.index');
                        })
                        ->hidden(fn (Organization $record) => $record->users->count() > 0)
                        ->action(fn (Organization $record) => $record->delete()),

                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.user-type-table');
    }
}
