<?php

namespace App\Http\Livewire\Profil;

use App\Models\Menu;
use Livewire\Component;
use Filament\Infolists\Infolist;
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

    public function userViewProfil(Infolist $infolist): Infolist
    {


        return $infolist->record($this->user)->schema([]);
    }
    public function render()
    {
        return view('livewire.profil.menu-infolist-view');
    }
}
