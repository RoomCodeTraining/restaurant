<?php

namespace App\Http\Livewire\Users;

use App\Events\UserLocked;
use App\Events\UserUnlocked;
use App\Exports\UserExport;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\States\Order\Cancelled;
use App\Support\ActivityHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UsersTable extends DataTableComponent
{
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


    public function configure(): void
    {
        $this->setPrimaryKey('id')
           ->setSingleSortingDisabled()
           ->setHideReorderColumnUnlessReorderingEnabled()
           ->setFilterLayoutSlideDown()
           ->setRememberColumnSelectionDisabled()
           ->setSecondaryHeaderTrAttributes(function ($rows) {
               return ['class' => 'bg-gray-100'];
           })
           ->setSecondaryHeaderTdAttributes(function (Column $column, $rows) {
               if ($column->isField('id')) {
                   return ['class' => 'text-red-500'];
               }

               return ['default' => true];
           })
           ->setFooterTrAttributes(function ($rows) {
               return ['class' => 'bg-gray-100'];
           })
           ->setFooterTdAttributes(function (Column $column, $rows) {
               if ($column->isField('name')) {
                   return ['class' => 'text-green-500'];
               }

               return ['default' => true];
           })
        //    ->setUseHeaderAsFooterEnabled()
           ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function builder(): Builder
    {
        return User::query()
            ->with('role', 'accessCard');
        // ->when($this->getFilter('type'), fn ($query, $type) => $query->whereRelation('roles', 'name', $type))
        // ->when($this->getFilter('active'), fn ($query, $active) => $query->where('is_active', $active === 'yes'));
    }

    public function columns(): array
    {
        return [
            Column::make('Matricule', 'identifier')->searchable(),
            Column::make('Nom & Prénoms', 'full_name'),
            Column::make('Email', 'email')->searchable()->sortable(),
            Column::make('Contact', 'contact')->searchable(),
            Column::make("N° Carte", 'accessCard.identifier')->searchable(),
                // ->format(function ($value, $column, User $row) {
                //     dd($row);

                //     return $row->accessCard ? $row->accessCard->identifier : 'Aucune carte';
                // })
                // ->searchable(function ($builder, $term) {
                //     return $builder
                //         ->orWhereHas('accessCard', function ($query) use ($term) {
                //             $query->where('type', 'like', '%' . $term . '%');
                //         });
                // }),
            // Column::make('Profil')->format(fn ($val, $col, User $user) => $user->role->name),
            Column::make('Etat', 'id')->format(fn ($value, User $row, Column $column) => view('livewire.users.status', ['user' => $row])),
            Column::make('Actions', 'accessCard.id')->format(fn ($value, User $user, Column $column) => view('livewire.users.table-actions', ['user' => $user])),
        ];
    }


    public function filters(): array
    {
        return [
            // SelectFilter::make('Profil')
            //     ->filter(array_merge(['' => 'Tous les types'], Role::pluck('name', 'name')->toArray())),
            // SelectFilter::make('Etat du compte')
            //     ->select(array_merge(['' => 'Tous les états'], [ 'yes' => 'Actif', 'no' => 'Inactif', ])),
        ];
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

    public function confirmUserReset($userId)
    {
        $this->userIdBeingReset = $userId;
        $this->confirmingUserReset = true;
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

    public function lockUser()
    {
        $user = User::find($this->userIdBeingLocked);

        $user->update(['is_active' => false]);
        ActivityHelper::createActivity(
            $user,
            "Desactivation du compte de $user->full_name",
            'Mise a jour de compte',
        );
        UserLocked::dispatch($user);

        $this->confirmingUserLocking = false;
        $this->userIdBeingLocked = null;

        flasher('success', "L'utilisateur a été désactivé avec succès !");

        return redirect()->route('users.index');
    }




    public function deleteUser()
    {
        $user = User::find($this->userIdBeingDeletion);

        //Annulation des commandes de l"utilisateur a supprimer

        $user->orders->filter(function ($order) {
            return $order->state->title() === "Commande effectuée";
        })->each(fn ($order) => $order->update(['state' => Cancelled::class]));


        $identifier = Str::random(60);
        $user->update([
            'identifier' => $identifier,
            'email' => $identifier.'@'.$identifier.'.com',
        ]);

        ActivityHelper::createActivity(
            $user,
            "Suppression du compte de $user->full_name",
            'Suppression de compte',
        );

        $user->delete();

        $this->confirmingUserDeletion = false;

        $this->userIdBeingDeletion = null;

        session()->flash('success', "L'utilisateur a été supprimé avec succès !");

        return redirect()->route('users.index');
    }

    public function confirmUserUnlocking($userId)
    {
        $this->userIdBeingUnlocked = $userId;
        $this->confirmingUserUnlocking = true;
    }

    public function unlockUser()
    {
        $user = User::find($this->userIdBeingUnlocked);

        $user->update(['is_active' => true]);

        ActivityHelper::createActivity(
            $user,
            "Activation de compte de $user->full_name",
            'MLise a jour de compte',
        );

        UserUnlocked::dispatch($user);

        $this->confirmingUserUnlocking = false;

        $this->userIdBeingUnlocked = null;

        session()->flash('success', "L'utilisateur a été activé avec succès !");

        return redirect()->route('users.index');
    }

    public function exportQuota()
    {
        return Excel::download(new \App\Exports\QuotaExport, 'quotas.xlsx');
    }


    public function exportToUser()
    {
        return Excel::download(new UserExport(), 'utilisateurs.xlsx');
    }


    public function modalsView(): string
    {
        return 'livewire.users.modals';
    }
}