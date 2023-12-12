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
                ->aside()

->schema([
    Tabs::make('Inf')->tabs([
        Tabs\Tab::make('info')
        ->icon('heroicon-m-user')->schema([

                            ])->columns(2),
    ]),
]);
        }

    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
