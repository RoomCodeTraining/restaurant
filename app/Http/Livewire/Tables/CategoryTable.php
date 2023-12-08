<?php

namespace App\Http\Livewire\Tables;

use App\Models\EmployeeStatus;
use App\Support\ActivityHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class CategoryTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\EmployeeStatus::query()->withCount('users')->latest())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM')->searchable(),
                TextColumn::make('users_count')->label('NBR D\'ÉMPLOYÉS'),
            ])
            ->actions([
                    Action::make('Editer')
                        ->url(fn (EmployeeStatus $record): string => route('employeeStatuses.edit', $record))
                        ->color('info')
                        ->label('')
                        ->tooltip('Editer la catégorie professionnelle')
                        ->icon('heroicon-o-pencil-square'),

                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->label('')
                        ->tooltip('Supprimer la catégorie professionnelle')
                        ->modalHeading('Supprimer la catégorie professionnelle')
                        ->modalDescription('Etes-vous sûr de vouloir supprimer cette catégorie professionnelle ?')
                        ->color('danger')
                        ->hidden(fn (EmployeeStatus $record) => $record->users->count() > 0)
                        ->action(function ($record) {
                            $record->delete();
                            ActivityHelper::createActivity($record, 'Suppression de la catégorie professionnelle ' . $record->name, 'Suppression de la catégorie professionnelle');
                            Notification::make()->title('Suppression de la catégorie professionnelle')->success()->body('La catégorie professionnelle a été supprimée avec succès !')->send($record->user);
                        })
            ]);
    }

    public function render()
    {
        return view('livewire.tables.category-table');
    }
}