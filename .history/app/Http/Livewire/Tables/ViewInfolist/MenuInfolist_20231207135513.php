<?php

namespace App\Http\Livewire\Tables\ViewInfolist;

use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;

class MenuInfolist extends Component
{

    public function MenuView(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Détail du menu')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->aside()
                    ->schema([
                        TextEntry::make('title')
                        TextEntry::make('title')
                        TextEntry::make('title')
                        TextEntry::make('title')
                    ])->columns(2)
            ]);
    }

    public function render()
    {
        return view('livewire.tables.view-infolist.menu-infolist');
    }
}