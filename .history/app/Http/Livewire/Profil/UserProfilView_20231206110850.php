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
                            Tabs\Tab::make('Notifications')
                                ->icon('heroicon-m-bell')
                                ->schema([
                                    TextEntry::make('created_at')->label('Date de création du compte')->dateTime('d/m/Y H:i:s'),
                                    TextEntry::make('full_name')->label('Nom complet'),
                                    TextEntry::make('role.name')->label('Profil'),
                                    TextEntry::make('email')->label('Email'),
                                    TextEntry::make('contact')->label('Contact')
                                ])->columns(2),
                            // ...
                        ])
                        Tabs\Tab::make('Information sur le référant')
                        ->hidden(auth()->user()->isAdmin() || auth()->user()->isCustomerManager())
                        ->icon('heroicon-m-user')
                        // ->hidden(auth()->user()->isAdmin() || auth()->user()->isCustomerManager())
                        ->schema([
                            ImageEntry::make('identity_url_card_recto')->label('Pièce d\'identité Recto du souscripteur'),
                            ImageEntry::make('identity_url_card_verso')->label('Pièce d\'identité Verso du souscripteur'),

                            ImageEntry::make('identity_url_recto')->label('Pièce d\'identité Recto du référant'),
                            ImageEntry::make('identity_url_verso')->label('Pièce d\'identité Verso du référant'),

                            textEntry::make('plan_location')->label('Plan de localisation'),
                            //->url(fn (Souscription $record) => route('souscripteur.pdf.download', $record))
                            //     ->openUrlInNewTab(),
                            // ->formatStateUsing(fn (string $state): string => __("statuses.{$state}")),
                            TextEntry::make('contact_person')->label('Contact')

                        ])->columns(2),
                ])

        ]);
    }
    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
