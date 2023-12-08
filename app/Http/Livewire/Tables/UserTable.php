<?php

namespace App\Http\Livewire\Tables;

use App\Events\UserLocked;
use App\Events\UserUnlocked;
use App\Exports\UserExport;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Support\ActivityHelper;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class UserTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;


    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";
    public string $tableName = 'users';


    public array $filterNames = [
        'type' => 'Profil',
        'active' => 'Etat du compte',
    ];

    public array $bulkActions = [
        'exportToUser' => 'Export en Excel',
        'exportQuota' => 'Export quotas en Excel',
    ];



    public $userIdBeingLocked;
    public $confirmingUserLocking = false;

    public $userIdBeingUnlocked;
    public $confirmingUserUnlocking = false;

    public $userIdBeingDeletion;
    public $confirmingUserDeletion = false;

    public $userIdBeingLunch;
    public $confirmingUserLunch = false;

    public $userIdBeingReset;
    public $confirmingUserReset = false;

    public $userIdAccessCardBeingReset;
    public $confirmingUserAccessCardReset = false;

    public function table(Table $table): Table
    {

        return $table
            ->query(\App\Models\User::query()->latest())
            // ->paginated([10, 25, 50, 100, 'all'])
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
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->withFilename(date('d-m-Y') . '- Utilisateurs - export'),
                ]),

            ])
            ->filters([
                SelectFilter::make('user_type_id')
                    ->relationship('role', 'name'),

                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Actif',
                        '0' => 'Inactif',
                    ])
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
                ->tooltip('Desactiver')
                ->hidden(function (User $user) {
                    return   ! $user->isActive() || $user->id == auth()->user()->id;
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
                    return $user->id == auth()->user()->id;
                })
                ->requiresConfirmation(),
        ]);
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


    public function confirmPasswordReset()
    {
        $user = User::find($this->userIdBeingReset);
        $newPassword = Str::random(8);
        $user->update([
            'password' => bcrypt($newPassword),
            'password_changed_at' => now(),
        ]);

        $user->passwordHistories()->create([
            'password' => Hash::make($newPassword),
        ]);

        $user->notify(new PasswordResetNotification($newPassword));
        $this->confirmingUserReset = false;
        $this->userIdBeingReset = null;

        flasher('success', "Le mot de passe de l'utilisateur a été réinitialisé avec succès !");

        return redirect()->route('users.index');
    }

    public function lockUser(User $user) : void
    {
        $user->update(['is_active' => false]);

        ActivityHelper::createActivity(
            $user,
            "Désactivation du compte de $user->full_name",
            'Mise à jour de compte',
        );

        UserLocked::dispatch($user);
    }


    public function deleteUser(User $user) : void
    {

        $user->orders->filter(function ($order) {
            return $order->state instanceof \App\States\Order\Confirmed;
        })->each(fn ($order) => $order->update(['state' => Cancelled::class]));


        $identifier = Str::random(60);

        $user->update([
            'identifier' => $identifier,
            'email' => $identifier . '@' . $identifier . '.com',
        ]);

        ActivityHelper::createActivity(
            $user,
            "Suppression du compte de $user->full_name",
            'Suppression de compte',
        );

        $user->delete();
    }

    public function unlockUser(User $user) : void
    {

        $user->update(['is_active' => true]);

        ActivityHelper::createActivity(
            $user,
            "Activation de compte de $user->full_name",
            'MLise a jour de compte',
        );

        UserUnlocked::dispatch($user);
    }

    public function exportQuota()
    {
        return Excel::download(new \App\Exports\QuotaExport, 'quotas.xlsx');
    }


    public function exportToUser()
    {
        return Excel::download(new UserExport(), 'utilisateurs.xlsx');
    }

    public function render()
    {
        return view('livewire.tables.user-table');
    }
}