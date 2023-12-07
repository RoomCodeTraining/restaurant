<?php

namespace App\Http\Livewire\Tables\ViewInfolist;

use Livewire\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;

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
