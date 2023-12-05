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

                TextColumn::make('accessCard.id')->label('Actions')->formatStateUsing(fn (User $user) => view('livewire.users.table-actions', ['user' => $user]))


            ]);
        // ->actions([
        //     ViewAction::make()
        //         ->label('')
        //         ->icon('heroicon-o-eye')
        //         ->color('success')
        //         ->tooltip('Consulter')
        //         ->url(function (User $user) {
        //             return route('users.show', $user);
        //         }),
        //     EditAction::make()
        //         ->label('')
        //         ->icon('heroicon-o-pencil-square')
        //         ->color('info')
        //         ->tooltip('Modifier')
        //         ->url(function (User $user) {
        //             return route('users.edit', $user);
        //         }),
        //     DeleteAction::make()
        //         ->label('')
        //         ->icon('heroicon-o-lock-closed')
        //         ->action(function (User $user) {
        //             $user->is_active = false;
        //             $user->save();
        //             Notification::make()
        //                 ->title('Utilisateur désactivé')
        //                 ->body('L\'utilisateur a été désactivé avec succès.')
        //                 ->success()
        //                 ->send();

        //             return redirect()->route('users.index');
        //         })
        //         ->tooltip('Desactiver')
        //         ->hidden(function (User $user) {
        //             return   !$user->isActive() || $user->id == auth()->user()->id;
        //         })
        //         ->requiresConfirmation(),
        //     Action::make('reactivate')
        //         ->label('')
        //         ->icon('heroicon-o-lock-open')
        //         ->tooltip('Activer')
        //         ->hidden(function (User $user) {
        //             return $user->isActive();
        //         })
        //         ->requiresConfirmation()
        //         ->action(function (User $user) {
        //             $user->is_active = true;
        //             $user->save();
        //             Notification::make()
        //                 ->title('Utilisateur activé')
        //                 ->body('L\'utilisateur a été activé avec succès.')
        //                 ->success()
        //                 ->send();

        //             return redirect()->route('users.index');
        //         }),
        //     DeleteAction::make()
        //         ->label('')
        //         ->icon('heroicon-o-trash')
        //         ->action(function (User $user) {
        //             $user->delete();
        //             Notification::make()
        //                 ->title('Utilisateur supprimé')
        //                 ->body('L\'utilisateur a été supprimé avec succès.')
        //                 ->success()
        //                 ->send();

        //             return redirect()->route('users.index');
        //         })
        //         ->tooltip('Supprimer')
        //         ->hidden(function (User $user) {
        //             return $user->id == auth()->user()->id;
        //         })
        //         ->requiresConfirmation(),
        // ]);
    }

    public function confirmUserLocking($userId)
    {
        $this->userIdBeingLocked = $userId;
        $this->confirmingUserLocking = true;
    }

    public function confirmAccessCardReset($userId)
    {
        $this->userIdAccessCardBeingReset = $userId;
        $this->confirmingUserAccessCardReset = true;
    }

    public function confirmUserDeleting($userId)
    {
        $this->userIdBeingDeletion = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function confirmUserLunch($userId)
    {
        $this->userIdBeingLunch = $userId;
        $this->confirmingUserLunch = true;
    }

    public function restoreCurrentCard()
    {
        $user = User::find($this->userIdAccessCardBeingReset);

        $temporaryCard = \App\Models\AccessCard::where('user_id', $user->id)->where('type', 'temporary')->latest()->first();
        $currentCard = $user->accessCards()->where('type', 'primary')->latest()->first();

        $user->current_access_card_id = $currentCard->id;
        $user->save();


        $currentCard->update([
            'quota_lunch' => $temporaryCard->quota_lunch,
            'quota_breakfast' => $temporaryCard->quota_breakfast,
        ]);

        $temporaryCard->update([
            'is_used' => false,
            'quota_lunch' => 0,
            'quota_breakfast' => 0,
        ]);

        $this->confirmingUserAccessCardReset = false;

        $this->userIdAccessCardBeingReset = null;

        flasher('success', "La carte courante de l'utilisateur a été restauré!");

        return redirect()->route('users.index');
    }

    public function confirmLunch()
    {
        $user = User::find($this->userIdBeingLunch);

        $user->update(['is_entitled_breakfast' => true]);

        $this->confirmingUserLunch = false;

        $this->userIdBeingLunch = null;

        flasher('success', "L'utilisateur a été activé avec succès !");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}
