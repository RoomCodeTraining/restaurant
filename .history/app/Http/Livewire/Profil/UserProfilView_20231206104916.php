<?php

namespace App\Http\Livewire\Profil;

use App\Models\User;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class UserProfilView extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function userView(Infolist $infolist): Infolist
    {
        return $infolist->record($this->user)->schema([
            Section::make()
                ->description("Les informations de votre profil.")
                ->schema([
                    TextEntry::make('created_at')->label('Date de création du compte')->dateTime('d/m/Y H:i:s'),
                    TextEntry::make('full_name')->label('Nom complet'),
                    TextEntry::make('role.role')->label('Profil'),
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('phone')->label('Contact')

                ])->columns(2),
        ]);
    }
    public function render()
    {
        return view('livewire.profil.user-profil-view');
    }
}
