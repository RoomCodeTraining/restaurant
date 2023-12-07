<?php

namespace App\Http\Livewire\Tables\ViewInfolist;

use Livewire\Component;

class MenuInfolist extends Component
{

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.tables.view-infolist.menu-infolist');
    }
}
