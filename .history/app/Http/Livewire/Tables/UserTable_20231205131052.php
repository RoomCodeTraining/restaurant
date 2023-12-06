<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class UserTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {

        return $table
            ->query(\App\Models\User::query()->latest())
            ->paginated([10, 25, 50, 100, 'all'])
            ->columns([
                TextColumn::make('identifier')
                    ->label('Matricule')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')->label('NOM & PRENOMS'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('contact'),
                TextColumn::make('current_access_card_id')
                    ->label('N° carte')
                    ->formatStateUsing(function (User $row) {
                        return $row->current_access_card_id ? $row->accessCard->identifier : 'Aucune carte';
                    }),

                TextColumn::make('role.name')->label('PROFIL'),
                // TextColumn::make('department.name')->label('Département'),
                TextColumn::make('is_active')
                    ->label('ETAT')
                    ->badge()
                    ->color(fn (User $row) => $row->isActive() ? 'success' : 'danger')
                    ->formatStateUsing(function (User $row) {
                        return $row->isActive() ? 'Actif' : 'Inactif';
                    }),

                    TextColumn::make('accessCard.id')->('Actions')->formatStateUsing(fn ($value, User $user, Column $column) => view('livewire.users.table-actions', ['user' => $user])),


            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->tooltip('Consulter')
                    ->url(function (User $user) {
                        return route('users.show', $user);
                    }),
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->tooltip('Modifier')
                    ->url(function (User $user) {
                        return route('users.edit', $user);
                    }),
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-lock-closed')
                    ->action(function (User $user) {
                        $user->is_active = false;
                        $user->save();
                        Notification::make()
                            ->title('Utilisateur désactivé')
                            ->body('L\'utilisateur a été désactivé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    })
                    ->tooltip('Desactiver')
                    ->hidden(function (User $user) {
                        return   !$user->isActive() || $user->id == auth()->user()->id;
                    })
                    ->requiresConfirmation(),
                Action::make('reactivate')
                    ->label('')
                    ->icon('heroicon-o-lock-open')
                    ->tooltip('Activer')
                    ->hidden(function (User $user) {
                        return $user->isActive();
                    })
                    ->requiresConfirmation()
                    ->action(function (User $user) {
                        $user->is_active = true;
                        $user->save();
                        Notification::make()
                            ->title('Utilisateur activé')
                            ->body('L\'utilisateur a été activé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    }),
                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->action(function (User $user) {
                        $user->delete();
                        Notification::make()
                            ->title('Utilisateur supprimé')
                            ->body('L\'utilisateur a été supprimé avec succès.')
                            ->success()
                            ->send();

                        return redirect()->route('users.index');
                    })
                    ->tooltip('Supprimer')
                    ->hidden(function (User $user) {
                        return $user->id == auth()->user()->id;
                    })
                    ->requiresConfirmation(),
            ]);
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}
