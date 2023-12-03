<?php

namespace App\Http\Livewire\Orders;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class CheckInBreakfastTable extends Component implements HasForms, HasTable
{

    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.orders.check-in-breakfast-table');
    }
}