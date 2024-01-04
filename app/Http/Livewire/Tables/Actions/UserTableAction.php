<?php

namespace App\Http\Livewire\Tables\Actions;

use App\Events\UserLocked;
use App\Events\UserUnlocked;
use App\Models\User;
use App\States\Order\Cancelled;
use App\Support\ActivityHelper;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

            EditAction::make()
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
                ->requiresConfirmation(),


            Action::make('reactivate')
                ->label('')
                ->icon('heroicon-o-lock-open')
                ->tooltip('Activer')
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

            Action::make('show_lunch')
                ->label('')
                ->icon('heroicon-o-wrench-screwdriver')
                ->tooltip('A droit au petit déjeuner')
                ->color('info')
                ->hidden(fn (User $record) => $record->is_entitled_breakfast == 0),

            Action::make('reset_password')
                ->label('')
                ->icon('heroicon-o-arrow-path')
                ->tooltip('Réinitialiser le mot de passe')
                ->color('warning')
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
                ->requiresConfirmation(),
        ];
    }



    public function confirmLunch(User $user)
    {
        $user->update(['is_entitled_breakfast' => true]);

        ActivityHelper::createActivity($user, "Autorisation au collaborateur $user->full_name de prendre le déjeuner", 'Permission de prendre le petit déjeuner');
    }

    public function confirmPasswordReset(User $user): void
    {
        Password::sendResetLink(
            $user->only('email'),
        );

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

        $card = $user->currentAccessCard;

        if ($card) {
            $user->dettachAccessCard($card);
        }
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

    // public function exportQuota()
    // {
    //     return Excel::download(new \App\Exports\QuotaExport(), 'quotas.xlsx');
    // }
}