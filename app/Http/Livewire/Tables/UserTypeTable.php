<?php

namespace App\Http\Livewire\Tables;

use App\Models\UserType;
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

class UserTypeTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\UserType::query()->withCount('users')->latest())
            ->columns([
                TextColumn::make('created_at')->label('DATE DE CRÉATION')->searchable()->dateTime('d/m/Y'),
                TextColumn::make('name')->label('NOM')->searchable(),
                TextColumn::make('users_count')->label('NBR D\'ÉMPLOYÉS'),

            ])->actions([
                    Action::make('Editer')
                        ->label('')
                        ->tooltip('Editer le type d\'utilisateur')
                        ->color('info')
                        ->url(fn (UserType $record): string => route('userTypes.edit', $record))
                        ->icon('heroicon-o-pencil'),
                    Action::make('Supprimer')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->label('')
                        ->modalHeading('Supprimer le type d\'utilisateur')
                        ->modalDescription('Etes-vous sûr de vouloir supprimer ce type d\'utilisateur ?')
                        ->tooltip('Supprimer le type d\'utilisateur')
                        ->hidden(fn (UserType $record) => $record->users->count() > 0)
                        ->action(function (UserType $record) {
                            $record->delete();
                            ActivityHelper::createActivity($record, 'Suppression du type d\'utilisateur ' . $record->name, 'Suppression du type d\'utilisateur');
                            Notification::make()->title('Suppression du type d\'utilisateur')->success()->body('Le type d\'utilisateur a été supprimé avec succès !')->send($record->user);
                        }),
            ]);
    }
    public function render()
    {
        return view('livewire.tables.user-type-table');
    }
}