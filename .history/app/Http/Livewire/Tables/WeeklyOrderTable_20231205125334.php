<?php

namespace App\Http\Livewire\Tables;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class WeeklyOrderTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;


    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
