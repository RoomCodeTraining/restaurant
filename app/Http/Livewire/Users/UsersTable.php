<?php

namespace App\Http\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use App\Events\UserLocked;
use App\Exports\UserExport;
use Illuminate\Support\Str;
use App\Events\UserUnlocked;
use App\Notifications\PasswordResetNotification;
use App\States\Order\Cancelled;
use App\States\Order\Confirmed;
use App\Support\ActivityHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UsersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public array $filterNames = [
        'type' => 'Profil',
        'active' => 'Etat du compte',
    ];

     public array $bulkActions = [
        'exportToUser' => 'Export en Excel',
        'exportQuota' => 'Export quotas en Excel',
    ];

    public string $defaultSortColumn = 'created_at';
    public string $defaultSortDirection = 'desc';

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

    public function query(): Builder
    {
        return User::query()
            ->with('role', 'accessCard')
            ->when($this->getFilter('type'), fn ($query, $type) => $query->whereRelation('roles', 'name', $type))
            ->when($this->getFilter('active'), fn ($query, $active) => $query->where('is_active', $active === 'yes'));
    }

    public function columns(): array
    {
        return [
            Column::make('Matricule', 'identifier')->searchable(),
            Column::make('Nom & Prénoms', 'full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Email', 'email')->searchable(),
            Column::make('Contact', 'contact')->searchable(),
            Column::make('Carte')->format(fn ($val, $col, User $user) => $user->accessCard ? $user->accessCard->identifier : 'Aucune carte')
            ->searchable(function ($builder, $term) {
                return $builder
                    ->orWhereHas('accessCard', function ($query) use ($term) {
                        $query->where('identifier', 'like', '%' . $term . '%');
                    });
            }),
            Column::make('Profil')->format(fn ($val, $col, User $user) => $user->role->name),
            Column::make('Etat du compte')->format(fn ($val, $col, User $user) => view('livewire.users.status', ['user' => $user])),
            Column::make('Actions')->format(fn ($val, $col, User $user) => view('livewire.users.table-actions', ['user' => $user])),
        ];
    }

    public function filters(): array
    {
        return [
            'type' => Filter::make('Profil')
                ->select(array_merge(['' => 'Tous les types'], Role::pluck('name', 'name')->toArray())),
            'active' => Filter::make('Etat du compte')
                ->select(array_merge(['' => 'Tous les états'], [ 'yes' => 'Actif', 'no' => 'Inactif', ])),
        ];
    }

    public function confirmUserLocking($userId)
    {
        $this->userIdBeingLocked = $userId;
        $this->confirmingUserLocking = true;
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

    public function confirmLunch()
    {
        $user = User::find($this->userIdBeingLunch);

        $user->update(['is_entitled_breakfast' => true]);

        $this->confirmingUserLunch = false;

        $this->userIdBeingLunch = null;

        session()->flash('success', "L'utilisateur a été activé avec succès !");

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

        session()->flash('success', "Le mot de passe de l'utilisateur a été réinitialisé avec succès !");
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

        session()->flash('success', "L'utilisateur a été désactivé avec succès !");

        return redirect()->route('users.index');
    }




    public function deleteUser(){
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

    public function exportQuota(){
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
