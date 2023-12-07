<?php

namespace App\Http\Livewire\Tables\ViewInfolist;

use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class MenuInfolist extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public function MenuView(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Détail du menu')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->aside()
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('title'),
                        TextEntry::make('title'),
                        TextEntry::make('title')
                    ])->columns(2)
            ]);
    }

    public function render()
    {
        return view('livewire.tables.view-infolist.menu-infolist');
    }
}
