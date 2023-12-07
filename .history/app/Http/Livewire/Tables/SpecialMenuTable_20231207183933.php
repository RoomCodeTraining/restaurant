<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Component;

class SpecialMenuTable extends Component implements HasForms,HasTable
{
    use InteractsWithTable,InteractsWithForms
    public function render()
    {
        return view('livewire.tables.special-menu-table');
    }
}