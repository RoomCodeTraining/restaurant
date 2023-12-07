<?php

namespace App\Http\Livewire\Tables\ViewInfolist;

use Livewire\Component;
use Filament\Infolists\Infolist;

class MenuInfolist extends Component
{

    public function MenuView(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Rate limiting')
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->aside()
                    ->schema([
                        // ...
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.tables.view-infolist.menu-infolist');
    }
}
