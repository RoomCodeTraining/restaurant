<?php

namespace App\Http\Livewire\Profil;

use App\Models\Menu;
use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class MenuInfolistView extends Component implements HasInfolists, HasForms
{
    use InteractsWithForms, InteractsWithInfolists;

    public Menu $menu;

    public function mount(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function menuView(Infolist $infolist): Infolist
    {
        return $infolist->record($this->menu)->schema([
            Section::make('Les informations de votre compte')
                ->description('Ces informations concerne vos données personnelles en tant que employé, et vos accès pour les commandes')
                ->aside()
                ->schema([
                    TextEntry::make('name')->label('Entrée'),
                    TextEntry::make('category.name'),
                ])
        ]);
    }
    public function render()
    {
        return view('livewire.profil.menu-infolist-view');
    }
}
