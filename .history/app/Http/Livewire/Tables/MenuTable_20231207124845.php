<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class MenuTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    public function render()
    {
        return view('livewire.tables.menu-table');
    }
}
