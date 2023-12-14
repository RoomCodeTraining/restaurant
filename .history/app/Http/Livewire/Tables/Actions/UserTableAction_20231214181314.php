<?php

namespace App\Http\Livewire\Tables\Actions;

use App\Events\UserLocked;
use App\Events\UserUnlocked;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Support\ActivityHelper;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UserTableAction
{
    public function getActions(): array
    {
        return [
            ViewAction::make()
                ->label('')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->tooltip('Consulter')
                ->url(function (User $user) {
                    return route('users.show', $user);
                }),
            Action::make('top_up_card')
                ->label('')
                ->tooltip('Recharger la carte')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->hidden(function (User $user) {
                    return !$user->isActive() ||
                        !auth()
                            ->user()
                            ->hasRole(Role::ADMIN_RH) ||
                        !$user->currentAccessCard;
                })
                ->url(fn (User $user) => route('reload.card', $user->currentAccessCard)),
            EditAction::make()
                ->hidden(
                    fn () => !auth()
                        ->user()
                        ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL),
                )
                ->label('')
                ->icon('heroicon-o-pencil-square')
                ->color('secondary')
                ->tooltip('Modifier')
                ->url(function (User $user) {
                    return route('users.edit', $user);
                }),

            Action::make('lock')
                ->label('')
                ->icon('heroicon-o-lock-closed')
                ->hidden(
                    fn () => !auth()
                        ->user()
                        ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL),
                )
                ->color('danger')
                ->tooltip('Désactiver le compte')
                ->modalHeading('Désactiver le compte')
                ->modalDescription('Etes-vous sûr de vouloir désactiver ce compte ?')
                ->action(function (User $user) {
                    $this->lockUser($user);
                    Notification::make()
                        ->title('Utilisateur désactivé')
                        ->body('L\'utilisateur a été désactivé avec succès.')
                        ->success()
                        ->send();

                    return redirect()->route('users.index');
                })
                ->tooltip('Désactiver')
                ->hidden(function (User $user) {
                    return !$user->isActive() ||
                        $user->id == auth()->user()->id ||
                        !auth()
                            ->user()
                            ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL);
                })
                ->requiresConfirmation(),


            Action::make('reactivate')
                ->label('')
                ->icon('heroicon-o-lock-open')
                ->tooltip('Activer')
                ->hidden(function (User $user) {
                    return $user->isActive() ||
                        !auth()
                            ->user()
                            ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL);
                })
                ->requiresConfirmation()
                ->modalHeading('Activer le compte')
                ->modalDescription('Etes-vous sûr de vouloir activer ce compte ?')
                ->color('success')
                ->action(function (User $user) {
                    $this->unlockUser($user);
                    Notification::make()
                        ->title('Utilisateur activé')
                        ->body('L\'utilisateur a été activé avec succès.')
                        ->success()
                        ->send();

                    return redirect()->route('users.index');
                }),

            Action::make('reactivate')
                ->label('')
                ->icon('heroicon-o-wrench-screwdriver')
                ->tooltip('Le déjeuner')
                // ->hidden(function (User $user) {
                //     return $user->isActive() ||
                //         auth()
                //             ->user()
                //             ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL);
                // })
                ->requiresConfirmation()
                ->modalHeading('Activer le déjeuner')
                ->modalDescription('Etes-vous sûr de vouloir prendre le pétit déjeuner ?')
                ->color('success')
                ->action(function (User $user) {
                    $this->confirmLunch($user);
                    Notification::make()
                        ->title('Utilisateur a pris le dej')
                        ->body('L\'utilisateur a déja pris le dej.')
                        ->success()
                        ->send();

                    return redirect()->route('users.index');
                }),

            Action::make('reset_password')
                ->label('')
                ->icon('heroicon-o-arrow-path')
                ->tooltip('Réinitialiser le mot de passe')

                ->color('warning')
                ->hidden(function (User $user) {
                    return $user->id == auth()->user()->id ||
                        !$user->isActive() ||
                        !auth()
                            ->user()
                            ->hasRole(Role::ADMIN);
                })
                ->requiresConfirmation()
                ->modalHeading('Réinitialiser le mot de passe')
                ->modalDescription('Etes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?')
                ->action(function (User $user) {
                    $this->confirmPasswordReset($user);

                    Notification::make()
                        ->title('Réinitialisation du mot de passe')
                        ->success()
                        ->body('Le mot de passe de l\'utilisateur a été réinitialisé avec succès !')
                        ->send();
                }),

            Action::make('restore_current_card')
                ->label('')
                ->icon('heroicon-o-credit-card')
                ->tooltip('Restaurer la carte primaire')
                ->color('secondary')
                ->hidden(function (User $user) {
                    if ($user->currentAccessCard) {
                        return $user->currentAccessCard->isCurrent() || !auth()->user()->hasRole(Role::ADMIN_RH)  ? true : false;
                    }

                    return true;
                })
                ->requiresConfirmation()
                ->modalHeading('Restaurer la carte')
                ->modalDescription('Etes-vous sûr de vouloir restaurer  la carte primaire de cet utilisateur ?')
                ->action(function (User $user) {
                    $this->restoreCurrentCard($user);
                    Notification::make()
                        ->title('Restauration de la carte primaire')
                        ->success()
                        ->body('La carte de l\'utilisateur a été restaurée avec succès !')
                        ->send();
                }),
            Action::make('delete')
                ->label('')
                ->icon('heroicon-o-trash')
                ->action(function (User $user) {
                    $this->deleteUser($user);
                    Notification::make()
                        ->title('Utilisateur supprimé')
                        ->body('L\'utilisateur a été supprimé avec succès.')
                        ->success()
                        ->send();

                    return redirect()->route('users.index');
                })
                ->tooltip('Supprimer le compte')
                ->color('danger')
                ->modalHeading('Supprimer le compte')
                ->modalDescription('Etes-vous sûr de vouloir supprimer ce compte ?')
                ->hidden(function (User $user) {
                    return $user->id == auth()->user()->id ||
                        !auth()
                            ->user()
                            ->hasRole(Role::ADMIN) && !auth()->user()->hasRole(Role::ADMIN_TECHNICAL);
                })
                ->requiresConfirmation(),
        ];
    }

    public function restoreCurrentCard(User $user): void
    {
        $temporaryCard = \App\Models\AccessCard::where('user_id', $user->id)
            ->where('type', 'temporary')
            ->latest()
            ->first();

        $currentCard = $user
            ->accessCards()
            ->where('type', 'primary')
            ->latest()
            ->first();

        $user->dettachAccessCard($temporaryCard);
        $user->switchAccessCard($currentCard);
    }

    public function confirmLunch(User $user)
    {
        $user->update(['is_entitled_breakfast' => true]);

        ActivityHelper::createActivity($user, "Autorisation au collaborateur $user->full_name de prendre le déjeuner", 'Permission de prendre le petit déjeuner');
    }

    public function confirmPasswordReset(User $user): void
    {
        $newPassword = Str::random(8);
        $user->update([
            'password' => bcrypt($newPassword),
            'password_changed_at' => now(),
        ]);

        $user->passwordHistories()->create([
            'password' => Hash::make($newPassword),
        ]);

        //dd($user);

        $user->notify(new PasswordResetNotification($newPassword));

        ActivityHelper::createActivity(auth()->user(), "Réinitialisation du compte de $user->full_name", 'Réinitialisation de compte');
    }

    public function lockUser(User $user): void
    {
        $user->update(['is_active' => false]);

        ActivityHelper::createActivity($user, "Désactivation du compte de $user->full_name", 'Mise à jour de compte');

        UserLocked::dispatch($user);
    }

    public function deleteUser(User $user): void
    {
        $user->orders
            ->filter(function ($order) {
                return $order->state instanceof \App\States\Order\Confirmed;
            })
            ->each(fn ($order) => $order->update(['state' => Cancelled::class]));

        $identifier = Str::random(60);

        $user->update([
            'identifier' => $identifier,
            'email' => $identifier . '@' . $identifier . '.com',
        ]);

        ActivityHelper::createActivity($user, "Suppression du compte de $user->full_name", 'Suppression de compte');

        $user->delete();
    }

    public function unlockUser(User $user): void
    {
        $user->update(['is_active' => true]);

        ActivityHelper::createActivity($user, "Activation de compte de $user->full_name", 'MLise a jour de compte');

        UserUnlocked::dispatch($user);
    }

    public function exportQuota()
    {
        return Excel::download(new \App\Exports\QuotaExport(), 'quotas.xlsx');
    }
}
