<?php

namespace App\Http\Livewire\Profil;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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
                ->aside()
                ->description('Ces informations concernent vos données personnelles ')
                ->schema([
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

                                TextEntry::make('organization_id')->label('Sociéte')
                                    ->formatStateUsing(function ($record) {
                                        return $record->organization_id ? $record->organization->name : 'Aucune sociéte';
                                    }),
                                TextEntry::make('is_active')
                                    ->formatStateUsing(function ($record) {
                                        return $record->is_active ? 'Actif' : 'Non Actif';
                                    })
                                    ->badge()
                                    ->color(fn ($record) => $record->is_blocked ? 'danger' : 'success')
                                    ->label('État du compte '),

                                //CONDITION SUR L'AFFICHAGE
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