<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\EmployeeStatus;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class CategoryTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\EmployeeStatus::query()->withCount('users'))
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM')->searchable(),
                TextColumn::make('users_count')->label('NBR D\'ÉMPLOYÉS'),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Editer')
                        ->url(fn (EmployeeStatus $record): string => route('employeeStatuses.edit', $record))
                        ->icon('heroicon-o-pencil'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->before(function (EmployeeStatus $record) {
                            //DepartmentDeleted::dispatch($record);
                            Notification::make()->title('Catégorie professionnelle supprimé supprimé avec succès !')->danger()->send();
                            return redirect()->route('employeeStatuses.index');
                        })
                        ->hidden(fn (EmployeeStatus $record) => $record->users->count() > 0)
                        ->action(fn (EmployeeStatus $record) => $record->delete()),

                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.category-table');
    }
}
