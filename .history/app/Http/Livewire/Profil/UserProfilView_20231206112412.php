<?php

namespace App\Http\Livewire\Profil;

use App\Models\User;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
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
    }

    public function userViewProfil(Infolist $infolist): Infolist
    {


        return $infolist->record($this->user)->schema([
            Section::make('Rate limiting')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->aside()
                ->schema([
                    Tabs::make('Tabs')

                        ->tabs([
                            Tabs\Tab::make('Mes informations personnelles')
                                ->icon('heroicon-m-bell')
                                ->schema([
                                    TextEntry::make('created_at')->label('Date de création du compte')->dateTime('d/m/Y H:i:s'),
                                    TextEntry::make('full_name')->label('Nom complet'),
                                    TextEntry::make('role.name')->label('Profil'),
                                    TextEntry::make('email')->label('Email'),
                                    TextEntry::make('contact')->label('Contact')
                                ])->columns(2),

                            Tabs\Tab::make('Information sur mon travail')
                                ->icon('heroicon-m-user')
                                ->schema([
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
                                        ->label('Etat du compte '),
                                ])->columns(2),
                        ])

                ])

        ]);
    }
    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
