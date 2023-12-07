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
            Section::make('Les détails du menu')
                ->description('Ces informations concerne le menu consulté ')
                ->aside()
                ->schema([
                    TextEntry::make('starter.name')->label('Entrée'),
                    TextEntry::make('mainDish.name')->label('Plat 1'),
                    TextEntry::make('id')->label('Plat 2')->formatStateUsing(fn (Menu $record) => $record->secondDish->name ? $record->secondDish->name : 'Aucun'),
                    TextEntry::make('dessert.name')->label('Dessert')

                ])->columns(2)
        ]);
    }
    public function render()
    {
        return view('livewire.profil.menu-infolist-view');
    }
}
