<?php

namespace App\Http\Livewire\Tables;

use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class WeeklyOrderTable extends Component implements HasTables, HasForms
{
    use InteractsWithTable, InteractsWithForms;
    public function render()
    {
        return view('livewire.tables.weekly-order-table');
    }
}
