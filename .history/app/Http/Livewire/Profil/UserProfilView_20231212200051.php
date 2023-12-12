<?php

namespace App\Http\Livewire\Profil;


use App\Models\User;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class UserProfilView extends Component implements HasInfolists, HasForms
{
    use InteractsWithForms, InteractsWithInfolists;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;

        // dd(Auth::user()->isFromLunchroom());
    }

    public function userViewProfil(Infolist $infolist): Infolist
    {


        return $infolist->record($this->user)->schema([
            Section::make('Les informations de votre compte')
                ->description('Ces informations concerne vos données personnelles en tant que employé, et vos accès pour les commandes')
                ->aside()->schema([
                    Tabs::make('Information de votre ')->tabs([
                        Tabs\Tab::make('informations de mon compte')
                            ->icon('heroicon-m-user')
                            ->schema([
                                ImageEntry::make('profile_photo_url')
                                    ->label('Avatar')
                                    ->height(40)
                                    ->circular(),
                                TextEntry::make('identifier')->label('Matricule / Identifiant'),
                                TextEntry::make('full_name')->label('Nom complet'),
                                TextEntry::make('role.name')->label('Profil'),
                                TextEntry::make('email')->label('Email'),
                                TextEntry::make('contact')->label('Contact'),

                                TextEntry::make('organization.name')->label('Sociéte')
                                    ->formatStateUsing(function ($record) {
                                        return $record->organization->name ? $record->organization->name : 'Aucune sociéte';
                                    }),
                                TextEntry::make('department.name')->label('Département'),
                                TextEntry::make('employeeStatus.name')->label('Catégorie socio-professionnelle'),
                                TextEntry::make('userType.name')->label('Type de collaborateur'),
                                TextEntry::make('is_active')
                                    ->formatStateUsing(function ($record) {
                                        return $record->is_active ? 'Actif' : 'Non Actif';
                                    })
                                    ->badge()
                                    ->color(fn ($record) => $record->is_blocked ? 'danger' : 'success')
                                    ->label('État du compte '),

                                //CONDITION SUR L'AFFICHAGE

                                TextEntry::make('updated_at')->label('Quota petit déjeuner')
                                    ->formatStateUsing(function ($record) {
                                        return $record->accessCard?->quota_breakfast ? $record->accessCard->quota_breakfast : '0';
                                    })->hidden(!Auth::user()->isFromLunchroom()),

                                TextEntry::make('created_at')->label('Quota déjeuner')
                                    ->formatStateUsing(function ($record) {
                                        return $record->accessCard?->quota_lunch ? $record->accessCard->quota_lunch : '0';
                                    })->hidden(!Auth::user()->isFromLunchroom()),

                                TextEntry::make('identifier')->label('Numéro de la carte')
                                    ->formatStateUsing(function ($record) {
                                        return $record->accessCard?->identifier ? $record->accessCard->identifier : 'Non défini';
                                    })->hidden(!Auth::user()->isFromLunchroom()),
                                TextEntry::make('contact')->label('Mode de paiement')
                                    ->formatStateUsing(function ($record) {
                                        return $record->accessCard?->paymentMethod->name ? $record->accessCard->paymentMethod->name : 'Non défini';
                                    })
                            ])->columns(2),
                    ]),
                ]),
        ]);
    }

    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
